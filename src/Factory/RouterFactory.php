<?php declare(strict_types=1);

namespace App\Factory;

/**
 * Import classes
 */
use Psr\Container\ContainerInterface;
use Sunrise\Http\Router\Loader\AnnotationDirectoryLoader;
use Sunrise\Http\Router\Router;

/**
 * RouterFactory
 */
class RouterFactory
{

    /**
     * Creates Router instance
     *
     * @param array $params
     * @param ContainerInterface $container
     *
     * @return Router
     */
    public function createRouter(array $params, ContainerInterface $container) : Router
    {
        $router = new Router();
        $router->addMiddleware(...$params['middlewares']);

        $loader = new AnnotationDirectoryLoader();
        $loader->setContainer($container);

        if (isset($params['metadata']['cache'])) {
            $loader->setCache($params['metadata']['cache']);
        }

        $loader->attach(...$params['metadata']['sources']);
        $router->load($loader);

        return $router;
    }
}
