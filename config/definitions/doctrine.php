<?php declare(strict_types=1);

use App\Factory\DoctrineFactory;
use Doctrine\Common\Cache\ArrayCache;

use function DI\create;
use function DI\factory;
use function DI\get;
use function DI\string;

return [
    'entityManager' => factory([DoctrineFactory::class, 'createEntityManager'])
        ->parameter('params', get('doctrine.configuration')),

    'doctrine.configuration' => [
        'metadata' => [
            'sources' => get('doctrine.configuration.metadata.sources'),
        ],
        'connection' => get('doctrine.configuration.connection'),
        'proxyDir' => get('doctrine.configuration.proxyDir'),
        'cache' => get('doctrine.configuration.cache'),
    ],

    'doctrine.configuration.metadata.sources' => [
        string('{app.root}/src/Entity'),
    ],

    'doctrine.configuration.connection' => [
        'url' => string('sqlite:///{app.root}/db/main.sqlite'),
    ],

    'doctrine.configuration.proxyDir' => null,

    'doctrine.configuration.cache' => create(ArrayCache::class),
];
