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

/**
 * @Route(
 *   name="home",
 *   path="/",
 *   methods={"GET"},
 * )
 *
 * @OpenApi\Operation(
 *   summary="Home page",
 *   responses={
 *     200: @OpenApi\Response(
 *       description="OK",
 *     ),
 *   },
 * )
 */
final class HomeController implements RequestHandlerInterface
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
        $response = (new ResponseFactory)->createResponse(200);

        $response->getBody()->write($this->container->get('twig')->load('welcome.html')->render());

        return $response;
    }
}
