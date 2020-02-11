<?php declare(strict_types=1);

use function DI\autowire;
use function DI\create;
use function DI\env;
use function DI\string;

return [

    /**
     * The application root
     */
    'app.root' => realpath(__DIR__ . '/../..'),

    /**
     * The application environment
     *
     * Use only the following values: dev, test, stage or prod
     */
    'app.env' => env('APP_ENV', 'dev'),

    /**
     * The application name
     *
     * Use only ASCII without whitespace characters
     */
    'app.name' => 'acme',

    /**
     * The application summary
     *
     * Use only markdown markup to format text
     */
    'app.summary' => 'Awesome Skeleton',

    /**
     * The application version
     *
     * Use only the following mask: MAJOR.MINOR.PATCH
     *
     * @link https://semver.org/
     */
    'app.version' => '0.1.0',

    /**
     * The application signature
     *
     * Use to accurate identification of the application (for example in logs)
     */
    'app.signature' => string('{app.name}@{app.version}.{app.env}'),

    /**
     * If set to false, fatal errors will not be displayed
     */
    'app.display_errors' => env('APP_DISPLAY_ERRORS', true),

    /**
     * The application commands
     */
    'commands' => [
        autowire(App\Command\GenerateOpenApiDocumentationCommand::class),
        create(App\Command\GenerateRoadRunnerSystemdUnitCommand::class),
    ],

    /**
     * The application middlewares
     */
    'middlewares' => [
        autowire(App\Middleware\CorsMiddleware::class),
        autowire(App\Middleware\ErrorHandlingMiddleware::class),
        create(Middlewares\ResponseTime::class),
        create(Middlewares\JsonPayload::class),
        create(Middlewares\UrlEncodePayload::class),
    ],
];
