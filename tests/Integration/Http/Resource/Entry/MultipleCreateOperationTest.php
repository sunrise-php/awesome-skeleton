<?php declare(strict_types=1);

namespace App\Tests\Integration\Http\Resource\Entry;

/**
 * Import classes
 */
use App\Controller\Entry\MultipleCreateController;
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
 * MultipleCreateOperationTest
 */
class MultipleCreateOperationTest extends TestCase
{
    use ContainerAwareTrait;
    use DatabaseHelperTrait;
    use ResponseBodyValidationTestCaseTrait;

    /**
     * @runInSeparateProcess
     *
     * @return void
     */
    public function testMultipleCreate() : void
    {
        $container = $this->getContainer();

        $this->createSchema($container->get('entityManager'));

        $this->assertSame(0, $container->get('service.entry')->countAll());

        $response = $container->get('router')->handle((new ServerRequestFactory)
            ->createServerRequest('PUT', '/api/v1/entry')
            ->withHeader('Content-Type', 'application/json')
            ->withParsedBody([
                [
                    'name' => 'foo',
                ],
                [
                    'name' => 'bar',
                ],
            ]));

        $this->assertSame(2, $container->get('service.entry')->countAll());

        $this->assertValidResponseBody(
            201,
            'application/json',
            MultipleCreateController::class,
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
    public function testMultipleCreateWithInvalidBody($invalidBody) : void
    {
        $container = $this->getContainer();

        $this->expectException(BadRequestException::class);

        $container->get('router')->handle((new ServerRequestFactory)
            ->createServerRequest('PUT', '/api/v1/entry')
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
                    [
                        // empty resource
                    ],
                ],
            ],
            [
                [
                    [
                        'name' => '',
                    ],
                ],
            ],
            [
                [
                    [
                        'name' => str_repeat('1', 256),
                    ],
                ],
            ],
        ];
    }
}
