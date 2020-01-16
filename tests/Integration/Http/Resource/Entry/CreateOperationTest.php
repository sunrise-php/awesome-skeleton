<?php declare(strict_types=1);

namespace App\Tests\Integration\Http\Resource\Entry;

/**
 * Import classes
 */
use App\Controller\Entry\CreateController;
use App\Tests\ContainerAwareTrait;
use App\Tests\DatabaseHelpersTrait;
use App\Tests\ResponseBodyValidationTestCase;
use PHPUnit\Framework\TestCase;
use Sunrise\Http\ServerRequest\ServerRequestFactory;

/**
 * CreateOperationTest
 */
class CreateOperationTest extends TestCase
{
    use ContainerAwareTrait;
    use DatabaseHelpersTrait;
    use ResponseBodyValidationTestCase;

    /**
     * @runInSeparateProcess
     *
     * @return void
     */
    public function testCreate() : void
    {
        $container = $this->getContainer();

        $this->createSchema($container->get('entityManager'));

        $this->assertSame(0, $container->get('service.entry')->countAll());

        $response = $container->get('router')->handle((new ServerRequestFactory)
            ->createServerRequest('POST', '/api/v1/entry')
            ->withHeader('Content-Type', 'application/json')
            ->withParsedBody([
                'name' => 'foo',
            ]));

        $this->assertSame(1, $container->get('service.entry')->countAll());

        $this->assertValidResponseBody(201, 'application/json', CreateController::class, $response);
    }
}

