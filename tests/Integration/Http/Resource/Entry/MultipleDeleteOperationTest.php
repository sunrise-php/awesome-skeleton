<?php declare(strict_types=1);

namespace App\Tests\Integration\Http\Resource\Entry;

/**
 * Import classes
 */
use App\Controller\Entry\MultipleDeleteController;
use App\Tests\ContainerAwareTrait;
use App\Tests\DatabaseHelperTrait;
use App\Tests\ResponseBodyValidationTestCaseTrait;
use PHPUnit\Framework\TestCase;
use Sunrise\Http\ServerRequest\ServerRequestFactory;
use Sunrise\Http\Router\Exception\BadRequestException;

/**
 * MultipleDeleteOperationTest
 */
class MultipleDeleteOperationTest extends TestCase
{
    use ContainerAwareTrait;
    use DatabaseHelperTrait;
    use ResponseBodyValidationTestCaseTrait;

    /**
     * @runInSeparateProcess
     *
     * @return void
     */
    public function testMultipleDelete() : void
    {
        $container = $this->getContainer();

        $this->createSchema($container->get('entityManager'));

        $this->assertSame(0, $container->get('service.entry')->countAll());

        $container->get('service.entry')->multipleCreate(
            [
                'name' => 'foo',
            ],
            [
                'name' => 'bar',
            ]
        );

        $this->assertSame(2, $container->get('service.entry')->countAll());

        $response = $container->get('router')->handle((new ServerRequestFactory)
            ->createServerRequest('DELETE', '/api/v1/entry')
            ->withHeader('Content-Type', 'application/json')
            ->withParsedBody([
                1,
                2,
            ]));

        $this->assertSame(0, $container->get('service.entry')->countAll());

        $this->assertValidResponseBody(
            200,
            'application/json',
            MultipleDeleteController::class,
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
    public function testMultipleDeleteWithInvalidBody($invalidBody) : void
    {
        $container = $this->getContainer();

        $this->expectException(BadRequestException::class);

        $container->get('router')->handle((new ServerRequestFactory)
            ->createServerRequest('DELETE', '/api/v1/entry')
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
                    // empty body
                ],
            ],
            [
                [
                    null,
                ],
            ],
            [
                [
                    0,
                ],
            ],
            [
                [
                    'foo',
                ],
            ],
        ];
    }
}
