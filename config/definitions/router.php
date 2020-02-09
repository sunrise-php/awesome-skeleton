<?php declare(strict_types=1);

use Sunrise\Http\Router\Loader\AnnotationDirectoryLoader;
use Sunrise\Http\Router\Loader\CollectableFileLoader;
use Sunrise\Http\Router\Router;

use function DI\factory;

return [
    'router' => factory(function ($container) {
        $router = new Router();

        $source = realpath(__DIR__ . '/../../src/Controller');
        $loader = new AnnotationDirectoryLoader();
        $loader->setContainer($container);
        $loader->attach($source);
        $router->load($loader);

        $source = realpath(__DIR__ . '/../../config/routes');
        $loader = new CollectableFileLoader();
        $loader->attach($source);
        $router->load($loader);

        return $router;
    }),
];
