<?php declare(strict_types=1);

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\ConsoleRunner;

chdir(dirname(__DIR__));
require_once 'vendor/autoload.php';
$container = require 'config/container.php';

$entityManager = $container->get(EntityManager::class);
return ConsoleRunner::createHelperSet($entityManager);
