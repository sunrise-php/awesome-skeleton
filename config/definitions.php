<?php declare(strict_types=1);

return
[
    /**
     * Monolog
     *
     * @link https://github.com/Seldaek/monolog
     */
    Psr\Log\LoggerInterface::class => function ($container) {
        $handler = new Monolog\Handler\StreamHandler(__DIR__ . '/../app.log');
        $handler->setFormatter(new Monolog\Formatter\LineFormatter);

        $logger = new Monolog\Logger('app');
        $logger->pushHandler($handler);

        return $logger;
    },

    /**
     * Sunrise HTTP Router
     *
     * @link https://github.com/sunrise-php/http-router
     * @link https://github.com/sunrise-php/http-router-annotations-support
     * @link https://github.com/middlewares/utils/pull/11
     */
    Sunrise\Http\Router\RouterInterface::class => function ($container) {
        Middlewares\Utils\Factory::setResponseFactory(new Sunrise\Http\Factory\ResponseFactory);
        Middlewares\Utils\Factory::setServerRequestFactory(new Sunrise\Http\Factory\ServerRequestFactory);
        Middlewares\Utils\Factory::setStreamFactory(new Sunrise\Http\Factory\StreamFactory);
        Middlewares\Utils\Factory::setUriFactory(new Sunrise\Http\Factory\UriFactory);

        $router = new Sunrise\Http\Router\Router();
        $router->addMiddleware(new Middlewares\Whoops);
        $router->addMiddleware(new Middlewares\ResponseTime);
        $router->addMiddleware(new Middlewares\UrlEncodePayload);
        $router->addMiddleware(new Middlewares\JsonPayload);

        $loader = new Sunrise\Http\Router\AnnotationRouteLoader();
        $routes = $loader->load(__DIR__ . '/../src/Http/Controller', [$container, 'get']);
        $router->addRoutes($routes);

        return $router;
    },

    /**
     * Doctrine Entity Manager
     *
     * @link https://www.doctrine-project.org/
     * @link https://www.doctrine-project.org/projects/doctrine-orm/en/2.6/reference/advanced-configuration.html
     * @link http://srcmvn.com/blog/2011/11/10/doctrine-dbal-query-logging-with-monolog-in-silex
     */
    Doctrine\ORM\EntityManager::class => function ($container) {
        Doctrine\Common\Annotations\AnnotationRegistry::registerLoader('class_exists');

        $debug = in_array($container->get('env'), ['local', 'development', 'test']);

        $config = Doctrine\ORM\Tools\Setup::createAnnotationMetadataConfiguration(
            [__DIR__ . '/../src/Entity'],
            $debug,
            null,
            null,
            false
        );

        return Doctrine\ORM\EntityManager::create($container->get('database'), $config);
    },
];
