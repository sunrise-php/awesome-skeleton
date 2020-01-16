<?php declare(strict_types=1);

namespace App\Factory;

/**
 * Import classes
 */
use Psr\Container\ContainerInterface;
use Sunrise\Http\Router\OpenApi\Object\Info;
use Sunrise\Http\Router\OpenApi\OpenApi;

/**
 * OpenApiFactory
 *
 * Don't use this factory outside the container!
 */
final class OpenApiFactory
{

    /**
     * Creates OpenApi instance
     *
     * @param array $params
     * @param ContainerInterface $container
     *
     * @return OpenApi
     */
    public function createOpenApi(array $params, ContainerInterface $container) : OpenApi
    {
        $info = new Info(
            $container->get('app.name'),
            $container->get('app.version')
        );

        $openapi = new OpenApi($info);

        $openapi->addRoute(...$container->get('router')->getRoutes());

        return $openapi;
    }
}
