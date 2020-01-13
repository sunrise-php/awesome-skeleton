<?php declare(strict_types=1);

namespace App\Controller;

/**
 * Import classes
 */
use App\ContainerAwareTrait;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Sunrise\Http\Message\ResponseFactory;
use Sunrise\Http\Router\OpenApi\Object\Info;
use Sunrise\Http\Router\OpenApi\OpenApi;

/**
 * @Route(
 *   name="openapi",
 *   path="/openapi",
 *   methods={"get"}
 * )
 */
class OpenApiController implements RequestHandlerInterface
{
    use ContainerAwareTrait;

    /**
     * {@inheritDoc}
     *
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $info = new Info(
            $this->container->get('app.name'),
            $this->container->get('app.version')
        );

        $openapi = new OpenApi($info);

        $openapi->addRoute(...$this->container->get('router')->getRoutes());

        return (new ResponseFactory)->createJsonResponse(200, $openapi->toArray())
            ->withHeader('Access-Control-Allow-Origin', '*');
    }
}
