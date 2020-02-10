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
 * Based on the following package: neomerx/cors-psr7
 *
 * @see https://github.com/neomerx/cors-psr7
 * @see https://www.w3.org/TR/cors/
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
            $analyzer->setLogger($this->container->get('logger'));
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
     * Creates CORS analyzer settings from the given parameters
     *
     * @param array $params
     *
     * @return Settings
     */
    private function createSettings(array $params) : Settings
    {
        $settings = new Settings();

        $settings->init(
            $params['serverOriginScheme'],
            $params['serverOriginHost'],
            (int) $params['serverOriginPort']
        );

        $settings->setPreFlightCacheMaxAge(
            (int) $params['preFlightCacheMaxAge']
        );

        $params['forceAddMethods'] ?
        $settings->enableAddAllowedMethodsToPreFlightResponse() :
        $settings->disableAddAllowedMethodsToPreFlightResponse();

        $params['forceAddHeaders'] ?
        $settings->enableAddAllowedHeadersToPreFlightResponse() :
        $settings->disableAddAllowedHeadersToPreFlightResponse();

        $params['useCredentials'] ?
        $settings->setCredentialsSupported() :
        $settings->setCredentialsNotSupported();

        $params['allOriginsAllowed'] ?
        $settings->enableAllOriginsAllowed() :
        $settings->setAllowedOrigins($params['allowedOrigins']);

        $params['allMethodsAllowed'] ?
        $settings->enableAllMethodsAllowed() :
        $settings->setAllowedMethods($params['allowedMethods']);

        $params['allHeadersAllowed'] ?
        $settings->enableAllHeadersAllowed() :
        $settings->setAllowedHeaders($params['allowedHeaders']);

        $settings->setExposedHeaders($params['exposedHeaders']);

        $params['checkHost'] ?
        $settings->enableCheckHost() :
        $settings->disableCheckHost();

        return $settings;
    }

    /**
     * Adds the given headers to the given response
     *
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
