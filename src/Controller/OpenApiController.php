<?php declare(strict_types=1);

namespace App\Controller;

/**
 * Import classes
 */
use App\Http\AbstractRequestHandler;
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
final class OpenApiController extends AbstractRequestHandler implements RequestHandlerInterface
{

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

        return $this->json($openapi->toArray());
    }
}
