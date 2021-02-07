<?php declare(strict_types=1);

namespace App\Middleware;

/**
 * Import classes
 */
use App\ContainerAwareTrait;
use Arus\Http\Response\ResponseFactoryAwareTrait;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Sunrise\Http\Header\HeaderRetryAfter;
use DateTime;

/**
 * Import functions
 */
use function file_exists;

/**
 * DoormanMiddleware
 */
final class DoormanMiddleware implements MiddlewareInterface
{
    use ContainerAwareTrait;
    use ResponseFactoryAwareTrait;

    /**
     * {@inheritDoc}
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        // if the file exists, then the application is closed for maintenance...
        $file = $this->container->get('app.root') . '/.down';
        if (file_exists($file)) {
            return $this->createResponse(503)
                ->withHeaderObject(new HeaderRetryAfter(new DateTime('+5 minutes')));
        }

        return $handler->handle($request);
    }
}
