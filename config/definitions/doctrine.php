<?php declare(strict_types=1);

use Arus\Doctrine\Bridge\ManagerRegistry;
use Doctrine\Common\Cache\ArrayCache;

use function DI\autowire;
use function DI\create;
use function DI\env;
use function DI\get;
use function DI\string;

return [
    'doctrine' => autowire(ManagerRegistry::class),

    'doctrine.configuration' => [
        'default' => [
            'connection' => get('doctrine.configuration.default.connection'),
            'metadata_sources' => get('doctrine.configuration.default.metadata_sources'),
            'metadata_cache' => get('doctrine.configuration.default.metadata_cache'),
            'query_cache' => get('doctrine.configuration.default.query_cache'),
            'result_cache' => get('doctrine.configuration.default.result_cache'),
            'proxy_dir' => get('doctrine.configuration.default.proxy_dir'),
            'proxy_namespace' => get('doctrine.configuration.default.proxy_namespace'),
            'proxy_auto_generate' => get('doctrine.configuration.default.proxy_auto_generate'),
        ],
    ],

    'doctrine.configuration.default.connection' => [
        'url' => env('DATABASE_URL', 'mysql://user:password@127.0.0.1:3306/acme'),
    ],

    'doctrine.configuration.default.metadata_sources' => [string('{app.root}/src/Entity')],
    'doctrine.configuration.default.metadata_cache' => get('doctrine.configuration.default.default_cache'),
    'doctrine.configuration.default.query_cache' => get('doctrine.configuration.default.default_cache'),
    'doctrine.configuration.default.result_cache' => get('doctrine.configuration.default.default_cache'),
    'doctrine.configuration.default.default_cache' => create(ArrayCache::class),
    'doctrine.configuration.default.proxy_dir' => string('{app.root}/database/proxies'),
    'doctrine.configuration.default.proxy_namespace' => 'DoctrineProxies',
    'doctrine.configuration.default.proxy_auto_generate' => true,
];
