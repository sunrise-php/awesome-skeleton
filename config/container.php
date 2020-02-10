<?php declare(strict_types=1);

use DI\ContainerBuilder;

$env = getenv('APP_ENV') ?: 'dev';

$builder = new ContainerBuilder();
$builder->useAutowiring(true);
$builder->useAnnotations(true);

$builder->addDefinitions(
    ...glob(__DIR__ . '/definitions/*.php'),
    ...glob(__DIR__ . '/definitions/*.php.' . $env),
    ...glob(__DIR__ . '/definitions/*.php.local')
);

return $builder->build();
