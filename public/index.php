<?php declare(strict_types=1);

chdir(dirname(__DIR__));

require_once 'vendor/autoload.php';

$container = require 'config/container.php';

$application = $container->get(App\Http\App::class);

$application->run(Sunrise\Http\Factory\ServerRequestFactory::fromGlobals());

exit(1);
