<?php declare(strict_types=1);

namespace App\Controller;

/**
 * Import classes
 */
use App\ContainerAwareTrait;
use App\Http\ResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Sunrise\Http\Router\OpenApi\Object\Info;
use Sunrise\Http\Router\OpenApi\OpenApi;

/**
 * @Route(
 *   name="openapi",
 *   path="/openapi",
 *   methods={"GET"},
 * )
 *
 * @OpenApi\Operation(
 *   summary="OpenApi doc",
 *   responses={
 *     200: @OpenApi\Response(
 *       description="OK",
 *     ),
 *   },
 * )
 */
final class OpenApiController implements RequestHandlerInterface
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

        return (new ResponseFactory)->json($openapi->toArray(), 200);
    }
}
