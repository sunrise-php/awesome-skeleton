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
final class HomeRedirectController implements RequestHandlerInterface
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
        $uri = $this->container->get('router')->generateUri('home', []);

        return (new ResponseFactory)->createResponse(301)
            ->withHeader('Location', $uri);
    }
}
