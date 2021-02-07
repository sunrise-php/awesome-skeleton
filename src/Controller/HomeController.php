<?php declare(strict_types=1);

namespace App\Controller;

/**
 * Import classes
 */
use App\ContainerAwareTrait;
use Arus\Http\Response\ResponseFactoryAwareTrait;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * @Route(
 *   name="home",
 *   path="/",
 *   methods={"GET"},
 * )
 */
final class HomeController implements RequestHandlerInterface
{
    use ContainerAwareTrait;
    use ResponseFactoryAwareTrait;

    /**
     * {@inheritDoc}
     *
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        return $this->ok();
    }
}
