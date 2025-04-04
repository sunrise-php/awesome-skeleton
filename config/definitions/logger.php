<?php

declare(strict_types=1);

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

use function DI\create;
use function DI\env;
use function DI\get;
use function DI\string;

return [
    'logger.name' => string('{app.name}@{app.env}-{app.version}'),

    'logger.stream_handler.stream_uri' => 'php://stderr',
    'logger.stream_handler.logging_level' => env('LOGGING_LEVEL'),

    'logger.handlers' => [
        create(StreamHandler::class)
            ->constructor(
                stream: get('logger.stream_handler.stream_uri'),
                level: get('logger.stream_handler.logging_level'),
            ),
    ],

    'logger.processors' => [
    ],

    'logger.timezone' => create(DateTimeZone::class)
        ->constructor(
            get('app.timezone_identifier'),
        ),

    LoggerInterface::class => create(Logger::class)
        ->constructor(
            name: get('logger.name'),
            handlers: get('logger.handlers'),
            processors: get('logger.processors'),
            timezone: get('logger.timezone'),
        ),
];
