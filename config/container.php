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
        // main definitions...
        ...glob(__DIR__ . '/definitions/*.php'),
        ...glob(__DIR__ . '/definitions/*.php.' . $env),
        ...glob(__DIR__ . '/definitions/*.php.local'),

        // bundle definitions...
        ...glob(__DIR__ . '/bundles/*/definitions/*.php'),
        ...glob(__DIR__ . '/bundles/*/definitions/*.php.' . $env),
        ...glob(__DIR__ . '/bundles/*/definitions/*.php.local'),
    );

    if ('prod' === $env) {
        $builder->enableCompilation($root . '/cache');
        $builder->enableDefinitionCache('container');
        $builder->writeProxiesToFile(true, $root . '/cache');
    }

    return $builder->build();
})();
