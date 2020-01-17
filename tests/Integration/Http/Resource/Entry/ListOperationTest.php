<?php declare(strict_types=1);

namespace App\Tests\Integration\Http\Resource\Entry;

/**
 * Import classes
 */
use App\Controller\Entry\ListController;
use App\Tests\ContainerAwareTrait;
use App\Tests\DatabaseHelperTrait;
use App\Tests\ResponseBodyValidationTestCaseTrait;
use PHPUnit\Framework\TestCase;
use Sunrise\Http\ServerRequest\ServerRequestFactory;

/**
 * ListOperationTest
 */
class ListOperationTest extends TestCase
{
    use ContainerAwareTrait;
    use DatabaseHelperTrait;
    use ResponseBodyValidationTestCaseTrait;

    /**
     * @runInSeparateProcess
     *
     * @return void
     */
    public function testList() : void
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
            ->createServerRequest('GET', '/api/v1/entry'));

        $this->assertValidResponseBody(
            200,
            'application/json',
            ListController::class,
            $response
        );
    }

    /**
     * @runInSeparateProcess
     *
     * @return void
     */
    public function testEmptyList() : void
    {
        $container = $this->getContainer();

        $this->createSchema($container->get('entityManager'));

        $this->assertSame(0, $container->get('service.entry')->countAll());

        $response = $container->get('router')->handle((new ServerRequestFactory)
            ->createServerRequest('GET', '/api/v1/entry'));

        $this->assertValidResponseBody(
            200,
            'application/json',
            ListController::class,
            $response
        );
    }
}
