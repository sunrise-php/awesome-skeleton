<?php declare(strict_types=1);

namespace App\Tests\Integration\Http\Resource\Entry;

/**
 * Import classes
 */
use App\Controller\Entry\UpdateController;
use App\Tests\ContainerAwareTrait;
use App\Tests\DatabaseHelperTrait;
use App\Tests\ResponseBodyValidationTestCaseTrait;
use PHPUnit\Framework\TestCase;
use Sunrise\Http\ServerRequest\ServerRequestFactory;
use Sunrise\Http\Router\Exception\BadRequestException;

/**
 * Import functions
 */
use function str_repeat;

/**
 * UpdateOperationTest
 */
class UpdateOperationTest extends TestCase
{
    use ContainerAwareTrait;
    use DatabaseHelperTrait;
    use ResponseBodyValidationTestCaseTrait;

    /**
     * @runInSeparateProcess
     *
     * @return void
     */
    public function testUpdate() : void
    {
        $container = $this->getContainer();

        $this->createSchema($container->get('entityManager'));

        $this->assertSame(0, $container->get('service.entry')->countAll());

        $container->get('service.entry')->create([
            'name' => 'foo',
        ]);

        $this->assertSame(1, $container->get('service.entry')->countAll());

        $response = $container->get('router')->handle((new ServerRequestFactory)
            ->createServerRequest('PATCH', '/api/v1/entry/1')
            ->withHeader('Content-Type', 'application/json')
            ->withParsedBody([
                'name' => 'bar',
            ]));

        $this->assertValidResponseBody(
            200,
            'application/json',
            UpdateController::class,
            $response
        );
    }

    /**
     * @runInSeparateProcess
     *
     * @return void
     */
    public function testUpdateNonexistent() : void
    {
        $container = $this->getContainer();

        $this->createSchema($container->get('entityManager'));

        $this->assertSame(0, $container->get('service.entry')->countAll());

        $response = $container->get('router')->handle((new ServerRequestFactory)
            ->createServerRequest('PATCH', '/api/v1/entry/1')
            ->withHeader('Content-Type', 'application/json')
            ->withParsedBody([
                'name' => 'bar',
            ]));

        $this->assertValidResponseBody(
            404,
            'application/json',
            UpdateController::class,
            $response
        );
    }

    /**
     * @runInSeparateProcess
     *
     * @dataProvider invalidBodyProvider
     *
     * @param mixed $invalidBody
     *
     * @return void
     */
    public function testUpdateWithInvalidBody($invalidBody) : void
    {
        $container = $this->getContainer();

        $this->createSchema($container->get('entityManager'));

        $this->assertSame(0, $container->get('service.entry')->countAll());

        $container->get('service.entry')->create([
            'name' => 'foo',
        ]);

        $this->assertSame(1, $container->get('service.entry')->countAll());

        $this->expectException(BadRequestException::class);

        $container->get('router')->handle((new ServerRequestFactory)
            ->createServerRequest('PATCH', '/api/v1/entry/1')
            ->withHeader('Content-Type', 'application/json')
            ->withParsedBody($invalidBody));
    }

    /**
     * @return array
     */
    public function invalidBodyProvider() : array
    {
        return [
            [
                [
                    // empty
                ],
            ],
            [
                [
                    'name' => '',
                ],
            ],
            [
                [
                    'name' => str_repeat('1', 256),
                ],
            ],
        ];
    }
}
