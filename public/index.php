<?php declare(strict_types=1);

use App\Http\App;
use Sunrise\Http\Factory\ServerRequestFactory;

chdir(dirname(__DIR__));
require_once 'vendor/autoload.php';
$container = require 'config/container.php';

$application = $container->get(App::class);
$application->run(ServerRequestFactory::fromGlobals());

exit(1);
