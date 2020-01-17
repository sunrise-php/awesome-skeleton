<?php declare(strict_types=1);

use App\Factory\RouterFactory;

use function DI\factory;
use function DI\get;
use function DI\string;

return [
    'router' => factory([RouterFactory::class, 'createRouter'])
        ->parameter('params', get('router.configuration')),

    'router.configuration' => [
        'metadata' => [
            'sources' => get('router.configuration.metadata.sources'),
            'cache' => get('router.configuration.metadata.cache'),
        ],
        'middlewares' => get('router.configuration.middlewares'),
    ],

    'router.configuration.metadata.sources' => [
        string('{app.root}/src/Controller'),
    ],

    'router.configuration.metadata.cache' => null,

    'router.configuration.middlewares' => [],
];
