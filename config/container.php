<?php declare(strict_types=1);

use DI\Container;
use DI\ContainerBuilder;

return (function () : Container {
    $env = getenv('APP_ENV') ?: 'dev';

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
        $builder->enableCompilation(__DIR__ . '/../cache');
        $builder->enableDefinitionCache('container');
        $builder->writeProxiesToFile(true, __DIR__ . '/../cache');
    }

    return $builder->build();
})();
