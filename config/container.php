<?php declare(strict_types=1);

use DI\Container;
use DI\ContainerBuilder;

return (function () : Container {
    $env = getenv('APP_ENV') ?: 'dev';
    $root = realpath(__DIR__ . '/..');

    $builder = new ContainerBuilder();
    $builder->useAutowiring(true);
    $builder->useAnnotations(true);

    $builder->addDefinitions(
        ...glob(__DIR__ . '/definitions/*.php'),
        ...glob(__DIR__ . '/definitions/*.php.' . $env),
        ...glob(__DIR__ . '/definitions/*.php.local'),
    );

    if ('prod' === $env) {
        $builder->enableCompilation($root . '/cache');
        $builder->enableDefinitionCache('container');
        $builder->writeProxiesToFile(true, $root . '/cache');
    }

    return $builder->build();
})();
