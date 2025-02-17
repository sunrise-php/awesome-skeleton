<?php

declare(strict_types=1);

use Sunrise\Http\Router\OpenApi\OpenApiConfiguration;

use function DI\add;
use function DI\get;
use function DI\string;

return [
    'router.descriptor_loader.resources' => add([
        string('{app.root}/src/Controller'),
    ]),

    'router.error_handling_middleware.produced_languages' => [
    ],

    'router.openapi.initial_document' => [
        'openapi' => OpenApiConfiguration::VERSION,
        'info' => [
            'title' => string('{app.name}@{app.env}'),
            'version' => '1.0.0',
        ],
    ],

    'router.openapi.document_filename' => string('{app.root}/var/cache/openapi'),

    'router.openapi.default_timestamp_format' => get('app.output_timestamp_format'),
];
