<?php declare(strict_types=1);

namespace App\Controller;

/**
 * Import classes
 */
use App\Http\AbstractRequestHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * @Route(
 *   name="home.mirror",
 *   path="/home-mirror",
 *   methods={"GET"},
 * )
 *
 * @OpenApi\Operation(
 *   summary="Mirror for home page",
 *   description="Router-level redirect demonstration.",
 *   responses={
 *     200: @OpenApi\Response(
 *       description="OK",
 *     ),
 *   },
 * )
 */
final class HomeMirrorController extends AbstractRequestHandler implements RequestHandlerInterface
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
        return $this->container->get('router')->getRoute('home')->handle($request);
    }
}
