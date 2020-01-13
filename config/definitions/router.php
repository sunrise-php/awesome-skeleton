<?php declare(strict_types=1);

use App\Factory\RouterFactory;
use App\Middleware\DoctrinePersistentEntityManagerMiddleware;
use Middlewares\JsonPayload as JsonPayloadMiddleware;
use Middlewares\UrlEncodePayload as UrlEncodePayloadMiddleware;

use function DI\autowire;
use function DI\create;
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

    'router.configuration.middlewares' => [
        autowire(DoctrinePersistentEntityManagerMiddleware::class),
        create(JsonPayloadMiddleware::class),
        create(UrlEncodePayloadMiddleware::class),
    ],
];
