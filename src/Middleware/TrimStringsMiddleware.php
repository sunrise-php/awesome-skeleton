<?php declare(strict_types=1);

namespace App\Middleware;

/**
 * Import classes
 */
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Import functions
 */
use function array_walk_recursive;
use function is_array;
use function is_string;
use function trim;

/**
 * TrimStringsMiddleware
 */
final class TrimStringsMiddleware implements MiddlewareInterface
{

    /**
     * {@inheritDoc}
     *
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        $parsedBody = $request->getParsedBody();

        if (is_array($parsedBody)) {
            array_walk_recursive($parsedBody, function (&$value) {
                if (is_string($value)) {
                    $value = trim($value);
                }
            });

            $request = $request->withParsedBody($parsedBody);
        }

        return $handler->handle($request);
    }
}
