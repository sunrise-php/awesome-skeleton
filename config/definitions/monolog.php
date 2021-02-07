<?php declare(strict_types=1);

use Monolog\Handler\ErrorLogHandler;
use Monolog\Logger;

use function DI\create;
use function DI\get;

return [
    'logger' => create(Logger::class)->constructor(
        get('monolog.configuration.name'),
        get('monolog.configuration.handlers'),
        get('monolog.configuration.processors'),
    ),

    'monolog.configuration.name' => get('app.signature'),

    'monolog.configuration.handlers' => [
        create(ErrorLogHandler::class),
    ],

    'monolog.configuration.processors' => [],
];
