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
 *   name="home.redirect",
 *   path="/home-redirect",
 *   methods={"GET"},
 * )
 *
 * @OpenApi\Operation(
 *   summary="Redirect to home page",
 *   description="HTTP-level redirect demonstration.",
 *   responses={
 *     301: @OpenApi\Response(
 *       description="OK",
 *     ),
 *   },
 * )
 */
final class HomeRedirectController extends AbstractRequestHandler implements RequestHandlerInterface
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
        $uri = $this->container->get('router')->generateUri('home', []);

        return $this->empty(301)->withHeader('Location', $uri);
    }
}
