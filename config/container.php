<?php

declare(strict_types=1);

use DI\Container;
use DI\ContainerBuilder;

return (static function (): Container {
    $appEnv = $_ENV['APP_ENV'] ?? 'dev';

    $containerBuilder = new ContainerBuilder();

    // https://github.com/sunrise-php/coder
    $containerBuilder->addDefinitions(
        __DIR__ . '/../vendor/sunrise/coder/resources/definitions/coder.php',
        __DIR__ . '/../vendor/sunrise/coder/resources/definitions/codecs/json_codec.php',
        __DIR__ . '/../vendor/sunrise/coder/resources/definitions/codecs/url_encoded_codec.php',
    );

    // https://github.com/sunrise-php/http-message
    $containerBuilder->addDefinitions(
        __DIR__ . '/../vendor/sunrise/http-message/resources/definitions/psr17.php',
    );

    // https://github.com/sunrise-php/http-router
    $containerBuilder->addDefinitions(
        __DIR__ . '/../vendor/sunrise/http-router/resources/definitions/router.php',
        __DIR__ . '/../vendor/sunrise/http-router/resources/definitions/openapi.php',
        __DIR__ . '/../vendor/sunrise/http-router/resources/definitions/loaders/descriptor_loader.php',
        __DIR__ . '/../vendor/sunrise/http-router/resources/definitions/controllers/openapi_controller.php',
        __DIR__ . '/../vendor/sunrise/http-router/resources/definitions/controllers/swagger_controller.php',
        __DIR__ . '/../vendor/sunrise/http-router/resources/definitions/middlewares/error_handling_middleware.php',
        __DIR__ . '/../vendor/sunrise/http-router/resources/definitions/middlewares/payload_decoding_middleware.php',
        __DIR__ . '/../vendor/sunrise/http-router/resources/definitions/middlewares/string_trimming_middleware.php',
        __DIR__ . '/../vendor/sunrise/http-router/resources/definitions/parameter_resolvers/default_value_parameter_resolver.php',
        // __DIR__ . '/../vendor/sunrise/http-router/resources/definitions/parameter_resolvers/dependency_injection_parameter_resolver.php',
        __DIR__ . '/../vendor/sunrise/http-router/resources/definitions/parameter_resolvers/request_body_parameter_resolver.php',
        __DIR__ . '/../vendor/sunrise/http-router/resources/definitions/parameter_resolvers/request_cookie_parameter_resolver.php',
        __DIR__ . '/../vendor/sunrise/http-router/resources/definitions/parameter_resolvers/request_header_parameter_resolver.php',
        __DIR__ . '/../vendor/sunrise/http-router/resources/definitions/parameter_resolvers/request_query_parameter_resolver.php',
        __DIR__ . '/../vendor/sunrise/http-router/resources/definitions/parameter_resolvers/request_variable_parameter_resolver.php',
        __DIR__ . '/../vendor/sunrise/http-router/resources/definitions/response_resolvers/empty_response_resolver.php',
        __DIR__ . '/../vendor/sunrise/http-router/resources/definitions/response_resolvers/encodable_response_resolver.php',
    );

    // https://github.com/sunrise-php/hydrator
    $containerBuilder->addDefinitions(
        __DIR__ . '/../vendor/sunrise/hydrator/resources/definitions/hydrator.php',
    );

    // https://github.com/sunrise-php/translator
    $containerBuilder->addDefinitions(
        __DIR__ . '/../vendor/sunrise/translator/resources/definitions/translator_manager.php',
        __DIR__ . '/../vendor/sunrise/http-router/resources/definitions/translators.php',
        __DIR__ . '/../vendor/sunrise/hydrator/resources/definitions/translators.php',
    );

    $containerBuilder->addDefinitions(
        ...glob(__DIR__ . '/definitions/*.php'),
        ...glob(__DIR__ . '/definitions/' . $appEnv . '/*.php'),
    );

    if ($appEnv === 'prod') {
        $containerBuilder->enableCompilation(__DIR__ . '/../var/cache/container');
    }

    return $containerBuilder->build();
})();
