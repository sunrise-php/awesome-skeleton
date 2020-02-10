<?php declare(strict_types=1);

use function DI\get;
use function DI\string;

return [
    'migrations.configuration' => [
        'name' => get('migrations.configuration.name'),
        'table_name' => get('migrations.configuration.table_name'),
        'column_name' => get('migrations.configuration.column_name'),
        'column_length' => get('migrations.configuration.column_length'),
        'executed_at_column_name' => get('migrations.configuration.executed_at_column_name'),
        'directory' => get('migrations.configuration.directory'),
        'namespace' => get('migrations.configuration.namespace'),
        'organize_by_year' => get('migrations.configuration.organize_by_year'),
        'organize_by_year_and_month' => get('migrations.configuration.organize_by_year_and_month'),
        'custom_template' => get('migrations.configuration.custom_template'),
        'is_dry_run' => get('migrations.configuration.is_dry_run'),
        'all_or_nothing' => get('migrations.configuration.all_or_nothing'),
        'check_database_platform' => get('migrations.configuration.check_database_platform'),
    ],

    'migrations.configuration.name' => null,
    'migrations.configuration.table_name' => null,
    'migrations.configuration.column_name' => null,
    'migrations.configuration.column_length' => null,
    'migrations.configuration.executed_at_column_name' => null,
    'migrations.configuration.directory' => string('{app.root}/database/migrations'),
    'migrations.configuration.namespace' => 'DoctrineMigrations',
    'migrations.configuration.organize_by_year' => null,
    'migrations.configuration.organize_by_year_and_month' => null,
    'migrations.configuration.custom_template' => null,
    'migrations.configuration.is_dry_run' => null,
    'migrations.configuration.all_or_nothing' => true,
    'migrations.configuration.check_database_platform' => null,
];
