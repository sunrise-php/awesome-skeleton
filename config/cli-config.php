<?php declare(strict_types=1);

use Doctrine\ORM\Tools\Console\ConsoleRunner;

$container = require __DIR__ . '/container.php';

return ConsoleRunner::createHelperSet(
    $container->get('entityManager')
);
