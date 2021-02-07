<?php declare(strict_types=1);

namespace App\Middleware;

/**
 * Import classes
 */
use App\ContainerAwareTrait;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Sunrise\Http\Router\OpenApi\Middleware\RequestHeaderValidationMiddleware as BaseMiddleware;

/**
 * RequestHeaderValidationMiddleware
 */
final class RequestHeaderValidationMiddleware extends BaseMiddleware implements MiddlewareInterface
{
    use ContainerAwareTrait;

    /**
     * {@inheritDoc}
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        if ('prod' === $this->container->get('app.env')) {
            $this->useCache();
        }

        return parent::process($request, $handler);
    }
}
