<?php declare(strict_types=1);

namespace App\Middleware;

/**
 * Import classes
 */
use App\ContainerAwareTrait;
use Arus\Http\Response\ResponseFactoryAwareTrait;
use Neomerx\Cors\Analyzer;
use Neomerx\Cors\Strategies\Settings;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Cross Origin Resource Sharing Middleware
 *
 * @see https://github.com/neomerx/cors-psr7
 */
final class CorsMiddleware implements MiddlewareInterface
{
    use ContainerAwareTrait;
    use ResponseFactoryAwareTrait;

    /**
     * {@inheritDoc}
     *
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        $settings = $this->createSettings(
            $this->container->get('cors.configuration')
        );

        $analyzer = Analyzer::instance($settings);

        if ($this->container->get('cors.debug')) {
            $analyzer->setLogger(
                $this->container->get('logger')
            );
        }

        $cors = $analyzer->analyze($request);

        switch ($cors->getRequestType()) {
            case $cors::ERR_NO_HOST_HEADER:
            case $cors::ERR_ORIGIN_NOT_ALLOWED:
            case $cors::ERR_METHOD_NOT_SUPPORTED:
            case $cors::ERR_HEADERS_NOT_SUPPORTED:
                return $this->createResponse(403);

            case $cors::TYPE_PRE_FLIGHT_REQUEST:
                return $this->injectHeaders(
                    $this->createResponse(200),
                    $cors->getResponseHeaders()
                );

            case $cors::TYPE_REQUEST_OUT_OF_CORS_SCOPE:
                return $handler->handle($request);

            default:
                return $this->injectHeaders(
                    $handler->handle($request),
                    $cors->getResponseHeaders()
                );
        }
    }

    /**
     * @param array $params
     *
     * @return Settings
     */
    private function createSettings(array $params) : Settings
    {
        $settings = new Settings();

        // the object initialization...
        $settings->setAllowedOrigins([]);
        $settings->setAllowedMethods([]);
        $settings->setAllowedHeaders([]);
        $settings->setExposedHeaders([]);

        $settings->setServerOrigin(
            (string) $params['server_origin_scheme'],
            (string) $params['server_origin_host'],
            (int) $params['server_origin_port']
        );

        $settings->setPreFlightCacheMaxAge(
            (int) $params['pre_flight_cache_max_age']
        );

        $params['force_add_methods'] ?
        $settings->enableAddAllowedMethodsToPreFlightResponse() :
        $settings->disableAddAllowedMethodsToPreFlightResponse();

        $params['force_add_headers'] ?
        $settings->enableAddAllowedHeadersToPreFlightResponse() :
        $settings->disableAddAllowedHeadersToPreFlightResponse();

        $params['use_credentials'] ?
        $settings->setCredentialsSupported() :
        $settings->setCredentialsNotSupported();

        $params['all_origins_allowed'] ?
        $settings->enableAllOriginsAllowed() :
        $settings->setAllowedOrigins(
            (array) $params['allowed_origins']
        );

        $params['all_methods_allowed'] ?
        $settings->enableAllMethodsAllowed() :
        $settings->setAllowedMethods(
            (array) $params['allowed_methods']
        );

        $params['all_headers_allowed'] ?
        $settings->enableAllHeadersAllowed() :
        $settings->setAllowedHeaders(
            (array) $params['allowed_headers']
        );

        $settings->setExposedHeaders(
            (array) $params['exposed_headers']
        );

        $params['check_host'] ?
        $settings->enableCheckHost() :
        $settings->disableCheckHost();

        return $settings;
    }

    /**
     * @param ResponseInterface $response
     * @param array $headers
     *
     * @return ResponseInterface
     */
    private function injectHeaders(ResponseInterface $response, array $headers) : ResponseInterface
    {
        foreach ($headers as $name => $value) {
            $response = $response->withHeader($name, $value);
        }

        return $response;
    }
}
