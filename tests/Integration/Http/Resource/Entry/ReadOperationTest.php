<?php declare(strict_types=1);

namespace App\Tests\Integration\Http\Resource\Entry;

/**
 * Import classes
 */
use App\Controller\Entry\ReadController;
use App\Tests\ContainerAwareTrait;
use App\Tests\DatabaseHelperTrait;
use App\Tests\ResponseBodyValidationTestCaseTrait;
use PHPUnit\Framework\TestCase;
use Sunrise\Http\ServerRequest\ServerRequestFactory;

/**
 * ReadOperationTest
 */
class ReadOperationTest extends TestCase
{
    use ContainerAwareTrait;
    use DatabaseHelperTrait;
    use ResponseBodyValidationTestCaseTrait;

    /**
     * @runInSeparateProcess
     *
     * @return void
     */
    public function testRead() : void
    {
        $container = $this->getContainer();

        $this->createSchema($container->get('entityManager'));

        $this->assertSame(0, $container->get('service.entry')->countAll());

        $container->get('service.entry')->create([
            'name' => 'foo',
        ]);

        $this->assertSame(1, $container->get('service.entry')->countAll());

        $response = $container->get('router')->handle((new ServerRequestFactory)
            ->createServerRequest('GET', '/api/v1/entry/1'));

        $this->assertValidResponseBody(
            200,
            'application/json',
            ReadController::class,
            $response
        );
    }

    /**
     * @runInSeparateProcess
     *
     * @return void
     */
    public function testReadNonexistent() : void
    {
        $container = $this->getContainer();

        $this->createSchema($container->get('entityManager'));

        $this->assertSame(0, $container->get('service.entry')->countAll());

        $response = $container->get('router')->handle((new ServerRequestFactory)
            ->createServerRequest('GET', '/api/v1/entry/1'));

        $this->assertValidResponseBody(
            404,
            'application/json',
            ReadController::class,
            $response
        );
    }
}
