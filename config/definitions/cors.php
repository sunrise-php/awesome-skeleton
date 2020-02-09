<?php declare(strict_types=1);

use function DI\env;
use function DI\get;

return [
    'cors.debug' => env('CORS_DEBUG', false),

    'cors.configuration' => [
        'server_origin_scheme'     => get('cors.configuration.server_origin_scheme'),
        'server_origin_host'       => get('cors.configuration.server_origin_host'),
        'server_origin_port'       => get('cors.configuration.server_origin_port'),
        'pre_flight_cache_max_age' => get('cors.configuration.pre_flight_cache_max_age'),
        'force_add_methods'        => get('cors.configuration.force_add_methods'),
        'force_add_headers'        => get('cors.configuration.force_add_headers'),
        'use_credentials'          => get('cors.configuration.use_credentials'),
        'all_origins_allowed'      => get('cors.configuration.all_origins_allowed'),
        'allowed_origins'          => get('cors.configuration.allowed_origins'),
        'all_methods_allowed'      => get('cors.configuration.all_methods_allowed'),
        'allowed_methods'          => get('cors.configuration.allowed_methods'),
        'all_headers_allowed'      => get('cors.configuration.all_headers_allowed'),
        'allowed_headers'          => get('cors.configuration.allowed_headers'),
        'exposed_headers'          => get('cors.configuration.exposed_headers'),
        'check_host'               => get('cors.configuration.check_host'),
    ],

    // Server Origin URL
    'cors.configuration.server_origin_scheme' => env('CORS_SERVER_ORIGIN_SCHEME', 'http'),
    'cors.configuration.server_origin_host' => env('CORS_SERVER_ORIGIN_HOST', '127.0.0.1'),
    'cors.configuration.server_origin_port' => env('CORS_SERVER_ORIGIN_PORT', 80),

    // Pre-flight cache max period in seconds
    'cors.configuration.pre_flight_cache_max_age' => 0,

    // If allowed headers should be added when request headers are 'simple' and
    // non of them is 'Content-Type' (see #6.2.10 CORS)
    // @see http://www.w3.org/TR/cors/#resource-preflight-requests
    'cors.configuration.force_add_methods' => false,
    'cors.configuration.force_add_headers' => false,

    // If access with credentials is supported by the resource
    'cors.configuration.use_credentials' => false,

    // Allowed origins
    'cors.configuration.all_origins_allowed' => env('CORS_ALL_ORIGINS_ALLOWED', false),
    'cors.configuration.allowed_origins' => env('CORS_ALLOWED_ORIGINS', []),

    // Allowed methods
    // Security Note: you have to remember CORS is not access control system and you should not expect all
    // cross-origin requests will have pre-flights. For so-called 'simple' methods with so-called 'simple'
    // headers request will be made without pre-flight. Thus you can not restrict such requests with CORS
    // and should use other means.
    // For example method 'GET' without any headers or with only 'simple' headers will not have pre-flight
    // request so disabling it will not restrict access to resource(s).
    // You can read more on 'simple' methods at http://www.w3.org/TR/cors/#simple-method
    'cors.configuration.all_methods_allowed' => false,
    'cors.configuration.allowed_methods' => [
        'HEAD',
        'GET',
        'POST',
        'PUT',
        'PATCH',
        'DELETE',
        'PURGE',
    ],

    // Allowed headers
    // Security Note: you have to remember CORS is not access control system and you should not expect all
    // cross-origin requests will have pre-flights. For so-called 'simple' methods with so-called 'simple'
    // headers request will be made without pre-flight. Thus you can not restrict such requests with CORS
    // and should use other means.
    // For example method 'GET' without any headers or with only 'simple' headers will not have pre-flight
    // request so disabling it will not restrict access to resource(s).
    // You can read more on 'simple' headers at http://www.w3.org/TR/cors/#simple-header
    'cors.configuration.all_headers_allowed' => false,
    'cors.configuration.allowed_headers' => [
        'Content-Type',
        'api_key',
        'Authorization',
    ],

    // Headers other than the simple ones that might be exposed to user agent.
    'cors.configuration.exposed_headers' => [],

    // If request 'Host' header should be checked against server's origin.
    // Check of Host header is strongly encouraged by #6.3 CORS.
    // Header 'Host' must present for all requests rfc2616 14.23
    'cors.configuration.check_host' => true,
];
