<?php declare(strict_types=1);

return
[
	/**
	 * Monolog
	 *
	 * @link https://github.com/Seldaek/monolog
	 */
	Psr\Log\LoggerInterface::class => function($container)
	{
		$file = __DIR__ . '/../app.log';

		$handler = new Monolog\Handler\StreamHandler($file);
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
	Sunrise\Http\Router\RouterInterface::class => function($container)
	{
		Middlewares\Utils\Factory::setResponseFactory(new Sunrise\Http\Factory\ResponseFactory);
		Middlewares\Utils\Factory::setServerRequestFactory(new Sunrise\Http\Factory\ServerRequestFactory);
		Middlewares\Utils\Factory::setStreamFactory(new Sunrise\Http\Factory\StreamFactory);
		Middlewares\Utils\Factory::setUriFactory(new Sunrise\Http\Factory\UriFactory);

		$router = new Sunrise\Http\Router\Router();
		$loader = new Sunrise\Http\Router\AnnotationRouteLoader();

		$router->addMiddleware(new Middlewares\Whoops);
		$router->addMiddleware(new Middlewares\ResponseTime);
		$router->addMiddleware(new Middlewares\UrlEncodePayload);
		$router->addMiddleware(new Middlewares\JsonPayload);

		$routes = $loader->load(__DIR__ . '/../src/Http/Controller', [$container, 'get']);
		$router->addRoutes($routes);

		return $router;
	},

	/**
	 * Doctrine Entity Manager
	 *
	 * @link https://www.doctrine-project.org/
	 */
	Doctrine\ORM\EntityManager::class => function($container)
	{
		Doctrine\Common\Annotations\AnnotationRegistry::registerLoader('class_exists');

		$debug = in_array($container->get('env'), ['local', 'development']);

		$config = Doctrine\ORM\Tools\Setup::createAnnotationMetadataConfiguration([__DIR__ . '/../src/Entity'], $debug, null, null, false);

		$manager = Doctrine\ORM\EntityManager::create($container->get('database'), $config);

		return $manager;
	},
];
