<?php declare(strict_types=1);

namespace App\Middleware;

/**
 * Import classes
 */
use Sunrise\Http\Router\OpenApi\Middleware\RequestBodyValidationMiddleware as BaseRequestBodyValidationMiddleware;

/**
 * RequestBodyValidationMiddleware
 *
 * You can cache the `fetchJsonSchema` method to reduce memory consumption.
 *
 * @link https://github.com/sunrise-php/http-router-openapi
 */
final class RequestBodyValidationMiddleware extends BaseRequestBodyValidationMiddleware
{
}
