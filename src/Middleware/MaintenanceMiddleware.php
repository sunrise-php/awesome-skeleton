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
use Sunrise\Http\Factory\ResponseFactory;
use Sunrise\Http\Header\HeaderRetryAfter;
use DateTime;

/**
 * Import functions
 */
use function file_exists;

/**
 * MaintenanceMiddleware
 */
final class MaintenanceMiddleware implements MiddlewareInterface
{
    use ContainerAwareTrait;

    /**
     * {@inheritDoc}
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        $file = $this->container->get('app.root') . '/.down';

        if (file_exists($file)) {
            return (new ResponseFactory)->createResponse(503)
                ->withHeaderObject(new HeaderRetryAfter(new DateTime('+5 minutes')));
        }

        return $handler->handle($request);
    }
}
