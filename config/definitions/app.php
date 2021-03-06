<?php declare(strict_types=1);

use function DI\autowire;
use function DI\create;
use function DI\env;
use function DI\string;

return [

    /**
     * The application name
     *
     * Use only ASCII without whitespace characters
     */
    'app.name' => 'acme',

    /**
     * The application summary (short description)
     *
     * Use only markdown markup to format text
     */
    'app.summary' => 'Awesome Skeleton',

    /**
     * The application root
     *
     * Must contain the root path of the application
     */
    'app.root' => realpath(__DIR__ . '/../..'),

    /**
     * The application URL
     *
     * Must contain scheme, host and port (if not standard)
     */
    'app.url' => env('APP_URL', 'http://localhost:3000'),

    /**
     * The application environment
     *
     * Use only the following values: dev, test, stage or prod
     */
    'app.env' => env('APP_ENV', 'dev'),

    /**
     * If set to true, debug info will be logged
     *
     * In the production MUST be set to 0
     */
    'app.debug' => env('APP_DEBUG', '1'),

    /**
     * If set to true, fatal errors will not be displayed
     *
     * In the production MUST be set to 1
     */
    'app.silent' => env('APP_SILENT', '0'),

    /**
     * The application version
     *
     * Use only the following mask: MAJOR.MINOR.PATCH
     *
     * @link https://semver.org/
     */
    'app.version' => trim(`bash bin/version`),

    /**
     * The application signature
     *
     * Use to accurate identification of the application (for example in logs)
     */
    'app.signature' => string('{app.name}@{app.version}-{app.env}'),

    /**
     * The application commands
     */
    'commands' => [
        autowire(App\Command\GenerateOpenApiDocumentCommand::class),
        autowire(App\Command\GenerateRoadRunnerSystemdUnitCommand::class),
    ],

    /**
     * The application middlewares
     */
    'middlewares' => [
        autowire(App\Middleware\ErrorHandlingMiddleware::class),
        autowire(App\Middleware\DoormanMiddleware::class),
        create(Middlewares\ResponseTime::class),
        create(Middlewares\JsonPayload::class),
    ],
];
