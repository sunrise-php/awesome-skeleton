<?php declare(strict_types=1);

namespace App\Tests\Controller;

/**
 * Import classes
 */
use App\Tests\ContainerAwareTrait;
use PHPUnit\Framework\TestCase;
use Sunrise\Http\ServerRequest\ServerRequestFactory;

/**
 * OpenApiControllerTest
 */
class OpenApiControllerTest extends TestCase
{
    use ContainerAwareTrait;

    /**
     * @var string
     */
    private const ROUTE_NAME = 'openapi';

    /**
     * @return void
     *
     * @runInSeparateProcess
     */
    public function testHandle() : void
    {
        $container = $this->getContainer();

        $request = (new ServerRequestFactory)
            ->createServerRequest('GET', '/openapi.json');

        $response = $container->get('router')
            ->getRoute(self::ROUTE_NAME)
            ->handle($request);

        $this->assertSame(200, $response->getStatusCode());
    }
}
