<?php declare(strict_types=1);

use Sunrise\Http\Router\RequestHandler\QueueableRequestHandler;
use Sunrise\Http\ServerRequest\ServerRequestFactory;
use function Sunrise\Http\Router\emit;

require __DIR__ . '/../vendor/autoload.php';

$container = require __DIR__ . '/../config/container.php';

$router = $container->get('router');
$middlewares = $container->get('middlewares');

$handler = new QueueableRequestHandler($router);
$handler->add(...$middlewares);

$request = ServerRequestFactory::fromGlobals();

emit($handler->handle($request));

exit(0);
