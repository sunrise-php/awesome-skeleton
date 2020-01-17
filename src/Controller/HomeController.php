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
        $html = $this->container->get('twig')->render('welcome.html');

        return (new ResponseFactory)->html($html, 200);
    }
}
