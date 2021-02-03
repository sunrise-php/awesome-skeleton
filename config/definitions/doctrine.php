<?php declare(strict_types=1);

use Arus\Doctrine\Bridge\ManagerRegistry;
use Doctrine\Common\Cache\ArrayCache;

use function DI\autowire;
use function DI\create;
use function DI\decorate;
use function DI\env;
use function DI\get;
use function DI\string;

return [
    'doctrine' => autowire(ManagerRegistry::class),

    'doctrine.types' => [],

    'doctrine.configuration' => [
        'master' => [
            'connection'          => get('doctrine.configuration.master.connection'),
            'metadata_sources'    => get('doctrine.configuration.master.metadata_sources'),
            'metadata_cache'      => get('doctrine.configuration.master.metadata_cache'),
            'query_cache'         => get('doctrine.configuration.master.query_cache'),
            'result_cache'        => get('doctrine.configuration.master.result_cache'),
            'proxy_dir'           => get('doctrine.configuration.master.proxy_dir'),
            'proxy_namespace'     => get('doctrine.configuration.master.proxy_namespace'),
            'proxy_auto_generate' => get('doctrine.configuration.master.proxy_auto_generate'),
            'sql_logger'          => get('doctrine.configuration.master.sql_logger'),
        ],
        'slave' => [
            'connection'          => get('doctrine.configuration.slave.connection'),
            'metadata_sources'    => get('doctrine.configuration.slave.metadata_sources'),
            'metadata_cache'      => get('doctrine.configuration.slave.metadata_cache'),
            'query_cache'         => get('doctrine.configuration.slave.query_cache'),
            'result_cache'        => get('doctrine.configuration.slave.result_cache'),
            'proxy_dir'           => get('doctrine.configuration.slave.proxy_dir'),
            'proxy_namespace'     => get('doctrine.configuration.slave.proxy_namespace'),
            'proxy_auto_generate' => get('doctrine.configuration.slave.proxy_auto_generate'),
            'sql_logger'          => get('doctrine.configuration.slave.sql_logger'),
        ],
    ],

    'doctrine.configuration.master.connection'           => ['url' => env('DB_MASTER_URL')],
    'doctrine.configuration.master.metadata_sources'     => get('doctrine.configuration.default.metadata_sources'),
    'doctrine.configuration.master.metadata_cache'       => get('doctrine.configuration.default.metadata_cache'),
    'doctrine.configuration.master.query_cache'          => get('doctrine.configuration.default.query_cache'),
    'doctrine.configuration.master.result_cache'         => get('doctrine.configuration.default.result_cache'),
    'doctrine.configuration.master.proxy_dir'            => get('doctrine.configuration.default.proxy_dir'),
    'doctrine.configuration.master.proxy_namespace'      => get('doctrine.configuration.default.proxy_namespace'),
    'doctrine.configuration.master.proxy_auto_generate'  => get('doctrine.configuration.default.proxy_auto_generate'),
    'doctrine.configuration.master.sql_logger'           => get('doctrine.configuration.default.sql_logger'),

    'doctrine.configuration.slave.connection'            => ['url' => env('DB_SLAVE_URL')],
    'doctrine.configuration.slave.metadata_sources'      => get('doctrine.configuration.default.metadata_sources'),
    'doctrine.configuration.slave.metadata_cache'        => get('doctrine.configuration.default.metadata_cache'),
    'doctrine.configuration.slave.query_cache'           => get('doctrine.configuration.default.query_cache'),
    'doctrine.configuration.slave.result_cache'          => get('doctrine.configuration.default.result_cache'),
    'doctrine.configuration.slave.proxy_dir'             => get('doctrine.configuration.default.proxy_dir'),
    'doctrine.configuration.slave.proxy_namespace'       => get('doctrine.configuration.default.proxy_namespace'),
    'doctrine.configuration.slave.proxy_auto_generate'   => get('doctrine.configuration.default.proxy_auto_generate'),
    'doctrine.configuration.slave.sql_logger'            => get('doctrine.configuration.default.sql_logger'),

    'doctrine.configuration.default.metadata_sources'    => [string('{app.root}/src/Entity')],
    'doctrine.configuration.default.metadata_cache'      => get('doctrine.configuration.default.default_cache'),
    'doctrine.configuration.default.query_cache'         => get('doctrine.configuration.default.default_cache'),
    'doctrine.configuration.default.result_cache'        => get('doctrine.configuration.default.default_cache'),
    'doctrine.configuration.default.default_cache'       => create(ArrayCache::class),
    'doctrine.configuration.default.proxy_dir'           => string('{app.root}/cache'),
    'doctrine.configuration.default.proxy_namespace'     => 'DoctrineProxies',
    'doctrine.configuration.default.proxy_auto_generate' => true,
    'doctrine.configuration.default.sql_logger'          => null,

    'commands' => decorate(function ($previous, $container) {
        // Proxy Commands for the Doctrine Library...
        $additional = $container->get('doctrine')->getCommands();

        return array_merge($previous, $additional);
    }),
];
