<?php declare(strict_types=1);

use App\Factory\MonologFactory;
use Monolog\Handler\ErrorLogHandler;

use function DI\create;
use function DI\factory;
use function DI\get;

return [
    'logger' => factory([MonologFactory::class, 'createLogger'])
        ->parameter('params', get('monolog.configuration')),

    'monolog.configuration' => [
        'name' => get('monolog.configuration.name'),
        'handlers' => get('monolog.configuration.handlers'),
        'processors' => get('monolog.configuration.processors'),
    ],

    'monolog.configuration.name' => get('app.signature'),

    'monolog.configuration.handlers' => [
        create(ErrorLogHandler::class),
    ],

    'monolog.configuration.processors' => [],
];
