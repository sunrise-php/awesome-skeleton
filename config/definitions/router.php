<?php declare(strict_types=1);

use Sunrise\Http\Router\Loader\DescriptorDirectoryLoader;
use Sunrise\Http\Router\Router;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Psr16Cache as Cache;

use function DI\factory;
use function DI\string;

return [
    'router' => factory(function ($container) {
        $loader = new DescriptorDirectoryLoader();
        $loader->setContainer($container);
        $loader->setCache($container->get('router.configuration.metadata_cache'));
        $loader->attachArray($container->get('router.configuration.metadata_sources'));

        $router = new Router();
        $router->load($loader);

        return $router;
    }),

    'router.configuration.metadata_cache' => factory(function () {
        return new Cache(new ArrayAdapter());
    }),

    'router.configuration.metadata_sources' => [
        string('{app.root}/src/Controller'),
    ],
];
