<?php declare(strict_types=1);

use Arus\Doctrine\Bridge\CommandsProvider;
use Arus\Doctrine\Bridge\ManagerRegistry;
use Doctrine\Common\Cache\ArrayCache;

use function DI\autowire;
use function DI\create;
use function DI\decorate;
use function DI\env;
use function DI\get;
use function DI\string;

return [
    'commands' => decorate(function ($previous, $container) {
        // the provider contains commands for
        // the following Doctrine libraries: DBAL, ORM and Migrations.
        $provider = new CommandsProvider($container);

        return array_merge($previous, $provider->getCommands());
    }),

    'doctrine' => autowire(ManagerRegistry::class),

    'doctrine.configuration' => [
        'default' => [
            'connection'             => get('doctrine.configuration.default.connection'),
            'metadata_sources'       => get('doctrine.configuration.default.metadata_sources'),
            'metadata_cache'         => get('doctrine.configuration.default.metadata_cache'),
            'query_cache'            => get('doctrine.configuration.default.query_cache'),
            'result_cache'           => get('doctrine.configuration.default.result_cache'),
            'proxy_dir'              => get('doctrine.configuration.default.proxy_dir'),
            'proxy_namespace'        => get('doctrine.configuration.default.proxy_namespace'),
            'proxy_auto_generate'    => get('doctrine.configuration.default.proxy_auto_generate'),
        ],
    ],

    'doctrine.configuration.migrations' => [
        'name'                       => get('doctrine.configuration.migrations.name'),
        'table_name'                 => get('doctrine.configuration.migrations.table_name'),
        'column_name'                => get('doctrine.configuration.migrations.column_name'),
        'column_length'              => get('doctrine.configuration.migrations.column_length'),
        'executed_at_column_name'    => get('doctrine.configuration.migrations.executed_at_column_name'),
        'directory'                  => get('doctrine.configuration.migrations.directory'),
        'namespace'                  => get('doctrine.configuration.migrations.namespace'),
        'organize_by_year'           => get('doctrine.configuration.migrations.organize_by_year'),
        'organize_by_year_and_month' => get('doctrine.configuration.migrations.organize_by_year_and_month'),
        'custom_template'            => get('doctrine.configuration.migrations.custom_template'),
        'is_dry_run'                 => get('doctrine.configuration.migrations.is_dry_run'),
        'all_or_nothing'             => get('doctrine.configuration.migrations.all_or_nothing'),
        'check_database_platform'    => get('doctrine.configuration.migrations.check_database_platform'),
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

    'doctrine.configuration.migrations.name' => null,
    'doctrine.configuration.migrations.table_name' => null,
    'doctrine.configuration.migrations.column_name' => null,
    'doctrine.configuration.migrations.column_length' => null,
    'doctrine.configuration.migrations.executed_at_column_name' => null,
    'doctrine.configuration.migrations.directory' => string('{app.root}/database/migrations'),
    'doctrine.configuration.migrations.namespace' => 'DoctrineMigrations',
    'doctrine.configuration.migrations.organize_by_year' => null,
    'doctrine.configuration.migrations.organize_by_year_and_month' => null,
    'doctrine.configuration.migrations.custom_template' => null,
    'doctrine.configuration.migrations.is_dry_run' => null,
    'doctrine.configuration.migrations.all_or_nothing' => true,
    'doctrine.configuration.migrations.check_database_platform' => null,
];
