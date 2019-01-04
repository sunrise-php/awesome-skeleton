<?php declare(strict_types=1);

$containerBuilder = new DI\ContainerBuilder();
$containerBuilder->useAnnotations(true);

$containerBuilder->addDefinitions(__DIR__ . '/definitions.php');
$containerBuilder->addDefinitions(__DIR__ . '/environment.php');

return $containerBuilder->build();
