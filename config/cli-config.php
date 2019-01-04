<?php declare(strict_types=1);

chdir(dirname(__DIR__));

require_once 'vendor/autoload.php';

$container = require 'config/container.php';

$entityManager = $container->get(Doctrine\ORM\EntityManager::class);

return Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet($entityManager);
