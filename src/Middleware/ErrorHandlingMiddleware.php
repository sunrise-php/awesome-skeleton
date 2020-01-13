<?php declare(strict_types=1);

namespace App\Middleware;

/**
 * Import classes
 */
use App\ContainerAwareTrait;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Sunrise\Http\Message\ResponseFactory;
use Sunrise\Http\Router\Exception\BadRequestException;
use Sunrise\Http\Router\Exception\MethodNotAllowedException;
use Sunrise\Http\Router\Exception\RouteNotFoundException;
use Sunrise\Http\Router\Exception\UnsupportedMediaTypeException;
use Whoops\Run as Whoops;
use Whoops\Handler\JsonResponseHandler;
use Whoops\Handler\PlainTextHandler;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Handler\XmlResponseHandler;
use Throwable;

/**
 * Import functions
 */
use function implode;

/**
 * ErrorHandlingMiddleware
 */
final class ErrorHandlingMiddleware implements MiddlewareInterface
{
    use ContainerAwareTrait;

    /**
     * @var array
     */
    private $whoopsHandlersMap = [
        'application/json' => JsonResponseHandler::class,
        'application/xml' => XmlResponseHandler::class,
        'text/plain' => PlainTextHandler::class,
        'text/html' => PrettyPageHandler::class,
    ];

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
        try {
            return $handler->handle($request);
        } catch (RouteNotFoundException $exception) {
            return $this->handleRouteNotFound($request, $exception);
        } catch (MethodNotAllowedException $exception) {
            return $this->handleMethodNotAllowed($request, $exception);
        } catch (BadRequestException $exception) {
            return $this->handleBadRequest($request, $exception);
        } catch (UnsupportedMediaTypeException $exception) {
            return $this->handleUnsupportedMediaType($request, $exception);
        } catch (Throwable $exception) {
            return $this->handleException($request, $exception);
        }
    }

    /**
     * @param ServerRequestInterface $request
     * @param MethodNotAllowedException $exception
     *
     * @return ResponseInterface
     */
    private function handleMethodNotAllowed(
        ServerRequestInterface $request,
        MethodNotAllowedException $exception
    ) : ResponseInterface {
        return (new ResponseFactory)->createResponse(405)
            ->withHeader('Allow', implode(',', $exception->getAllowedMethods()));
    }

    /**
     * @param ServerRequestInterface $request
     * @param UnsupportedMediaTypeException $exception
     *
     * @return ResponseInterface
     */
    private function handleUnsupportedMediaType(
        ServerRequestInterface $request,
        UnsupportedMediaTypeException $exception
    ) : ResponseInterface {
        return (new ResponseFactory)->createResponse(415)
            ->withHeader('Accept', implode(',', $exception->getSupportedTypes()));
    }

    /**
     * @param ServerRequestInterface $request
     * @param BadRequestException $exception
     *
     * @return ResponseInterface
     */
    private function handleBadRequest(
        ServerRequestInterface $request,
        BadRequestException $exception
    ) : ResponseInterface {
        $mediaType = $this->fetchMediaType($request);

        if ('application/json' === $mediaType) {
            return (new ResponseFactory)->createJsonResponse(400, [
                'status' => 'error',
                'message' => $exception->getMessage(),
                'violations' => $exception->getViolations(),
            ]);
        }

        return $this->createHtmlResponse(400, 'errors/bad-request.html', [
            'message' => $exception->getMessage(),
            'violations' => $exception->getViolations(),
        ]);
    }

    /**
     * @param ServerRequestInterface $request
     * @param RouteNotFoundException $exception
     *
     * @return ResponseInterface
     */
    private function handleRouteNotFound(
        ServerRequestInterface $request,
        RouteNotFoundException $exception
    ) : ResponseInterface {
        $mediaType = $this->fetchMediaType($request);

        if ('application/json' === $mediaType) {
            return (new ResponseFactory)->createJsonResponse(404, [
                'status' => 'error',
                'message' => $exception->getMessage(),
            ]);
        }

        return $this->createHtmlResponse(404, 'errors/page-not-found.html', [
            'message' => $exception->getMessage(),
            'violations' => [],
        ]);
    }

    /**
     * Handlers the given exception via Whoops library
     *
     * @param ServerRequestInterface $request
     * @param Throwable $exception
     *
     * @return ResponseInterface
     *
     * @link https://github.com/filp/whoops
     */
    private function handleException(ServerRequestInterface $request, Throwable $exception) : ResponseInterface
    {
        $this->container->get('logger')->error($exception->getMessage(), [
            'exception' => $exception,
        ]);

        $whoops = new Whoops();
        $whoops->allowQuit(false);
        $whoops->sendHttpCode(false);
        $whoops->writeToOutput(false);

        $mediaType = $this->fetchMediaType($request);
        $whoopsHandler = new $this->whoopsHandlersMap[$mediaType];
        $whoops->pushHandler($whoopsHandler);

        $response = (new ResponseFactory)->createResponse(500)
            ->withHeader('Content-Type', $whoopsHandler->contentType());

        $response->getBody()->write($whoops->handleException($exception));

        return $response;
    }

    /**
     * Creates HTML response via Twig template engine
     *
     * @param int $status
     * @param string $template
     * @param array $context
     *
     * @return ResponseInterface
     */
    private function createHtmlResponse(int $status, string $template, array $context) : ResponseInterface
    {
        $response = (new ResponseFactory)->createResponse($status)
            ->withHeader('Content-Type', 'text/html');

        $response->getBody()->write($this->container->get('twig')
            ->load($template)
            ->render($context)
        );

        return $response;
    }

    /**
     * Fetches an expected media type from the given request for the client
     *
     * @param ServerRequestInterface $request
     *
     * @return string
     */
    private function fetchMediaType(ServerRequestInterface $request) : string
    {
        $accept = $request->getHeaderLine('Accept');

        $suspect = 'application/json';
        if (false !== stripos($accept, $suspect)) {
            return $suspect;
        }

        $suspect = 'application/xml';
        if (false !== stripos($accept, $suspect)) {
            return $suspect;
        }

        $suspect = 'text/plain';
        if (false !== stripos($accept, $suspect)) {
            return $suspect;
        }

        return 'text/html';
    }
}
