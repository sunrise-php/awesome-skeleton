<?php declare(strict_types=1);

use App\Factory\OpenApiFactory;

use function DI\factory;
use function DI\get;

return [
    'openapi' => factory([OpenApiFactory::class, 'createOpenApi'])
        ->parameter('params', get('openapi.configuration')),

    'openapi.configuration' => [],
];
