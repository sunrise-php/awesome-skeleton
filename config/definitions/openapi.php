<?php declare(strict_types=1);

use Sunrise\Http\Router\OpenApi\Object\Info;
use Sunrise\Http\Router\OpenApi\OpenApi;

use function DI\factory;

return [
    'openapi' => factory(function ($container) {
        $info = new Info(
            $container->get('app.name'),
            $container->get('app.version')
        );

        $info->setDescription(
            $container->get('app.summary')
        );

        $openapi = new OpenApi($info);
        $openapi->includeUndescribedOperations(false);

        $openapi->addRoute(
            ...$container->get('router')->getRoutes()
        );

        return $openapi;
    }),
];
