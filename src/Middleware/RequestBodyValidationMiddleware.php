<?php declare(strict_types=1);

namespace App\Middleware;

/**
 * Import classes
 */
use App\ContainerAwareTrait;
use Psr\Http\Message\ServerRequestInterface;
use Sunrise\Http\Router\OpenApi\Middleware\RequestBodyValidationMiddleware as BaseRequestBodyValidationMiddleware;

/**
 * RequestBodyValidationMiddleware
 *
 * You can cache this method to reduce memory consumption.
 *
 * @link https://github.com/sunrise-php/http-router-openapi
 */
final class RequestBodyValidationMiddleware extends BaseRequestBodyValidationMiddleware
{
    use ContainerAwareTrait;

    /**
     * {@inheritDoc}
     */
    protected function fetchJsonSchema(ServerRequestInterface $request)
    {
        $jsonSchema = parent::fetchJsonSchema($request);

        return $jsonSchema;
    }
}
