<?php declare(strict_types=1);

use DI\ContainerBuilder;
use Symfony\Component\Dotenv\Dotenv;

(new Dotenv(true))->load(__DIR__ . '/../.env');

$env = getenv('APP_ENV') ?: 'dev';

$builder = new ContainerBuilder();
$builder->useAnnotations(true);

$builder->addDefinitions(
    ...glob(__DIR__ . '/definitions/*.php'),
    ...glob(__DIR__ . '/definitions/*.php.' . $env),
    ...glob(__DIR__ . '/definitions/*.php.local')
);

return $builder->build();
