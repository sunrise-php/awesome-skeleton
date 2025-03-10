<?php

declare(strict_types=1);

use DI\Container;
use Sunrise\Http\Message\ServerRequestFactory;
use Sunrise\Http\Router\RouterInterface;

use function Sunrise\Http\Router\emit;

require_once __DIR__ . '/../config/bootstrap.php';

/** @var Container $container */
$container = require_once __DIR__ . '/../config/container.php';

emit($container->get(RouterInterface::class)->handle(ServerRequestFactory::fromGlobals()));

if (function_exists('fastcgi_finish_request')) {
    fastcgi_finish_request();
}

exit(0);
