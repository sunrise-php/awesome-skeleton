<?php declare(strict_types=1);

use function DI\env;
use function DI\get;

return [
    'cors.debug' => env('CORS_DEBUG', false),

    'cors.configuration' => [
        'serverOriginScheme' => get('cors.configuration.serverOriginScheme'),
        'serverOriginHost' => get('cors.configuration.serverOriginHost'),
        'serverOriginPort' => get('cors.configuration.serverOriginPort'),
        'preFlightCacheMaxAge' => get('cors.configuration.preFlightCacheMaxAge'),
        'forceAddMethods' => get('cors.configuration.forceAddMethods'),
        'forceAddHeaders' => get('cors.configuration.forceAddHeaders'),
        'useCredentials' => get('cors.configuration.useCredentials'),
        'allOriginsAllowed' => get('cors.configuration.allOriginsAllowed'),
        'allowedOrigins' => get('cors.configuration.allowedOrigins'),
        'allMethodsAllowed' => get('cors.configuration.allMethodsAllowed'),
        'allowedMethods' => get('cors.configuration.allowedMethods'),
        'allHeadersAllowed' => get('cors.configuration.allHeadersAllowed'),
        'allowedHeaders' => get('cors.configuration.allowedHeaders'),
        'exposedHeaders' => get('cors.configuration.exposedHeaders'),
        'checkHost' => get('cors.configuration.checkHost'),
    ],

    'cors.configuration.serverOriginScheme' => env('CORS_SERVER_ORIGIN_SCHEME', 'http'),
    'cors.configuration.serverOriginHost' => env('CORS_SERVER_ORIGIN_HOST', '127.0.0.1'),
    'cors.configuration.serverOriginPort' => env('CORS_SERVER_ORIGIN_PORT', 3000),

    'cors.configuration.preFlightCacheMaxAge' => 0,

    'cors.configuration.forceAddMethods' => false,
    'cors.configuration.forceAddHeaders' => false,

    'cors.configuration.useCredentials' => false,

    'cors.configuration.allOriginsAllowed' => false,
    'cors.configuration.allowedOrigins' => [],

    'cors.configuration.allMethodsAllowed' => false,
    'cors.configuration.allowedMethods' => [
        'HEAD',
        'GET',
        'POST',
        'PUT',
        'PATCH',
        'DELETE',
        'PURGE',
    ],

    'cors.configuration.allHeadersAllowed' => false,
    'cors.configuration.allowedHeaders' => [
        'Content-Type',
        'api_key',
        'Authorization',
    ],

    'cors.configuration.exposedHeaders' => [],

    'cors.configuration.checkHost' => true,
];
