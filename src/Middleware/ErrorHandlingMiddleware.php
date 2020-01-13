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
use Whoops\Handler\PrettyPageHandler;
use Throwable;

/**
 * Import functions
 */
use function implode;
use function stripos;

/**
 * ErrorHandlingMiddleware
 */
final class ErrorHandlingMiddleware implements MiddlewareInterface
{
    use ContainerAwareTrait;

    /**
     * {@inheritDoc}
     *
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     */
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ) : ResponseInterface {
        try {
            return $handler->handle($request);
        } catch (BadRequestException $e) {
            return $this->handleBadRequest($request, $e);
        } catch (MethodNotAllowedException $e) {
            return $this->handleMethodNotAllowed($request, $e);
        } catch (RouteNotFoundException $e) {
            return $this->handleRouteNotFound($request, $e);
        } catch (UnsupportedMediaTypeException $e) {
            return $this->handleUnsupportedMediaType($request, $e);
        } catch (Throwable $e) {
            return $this->handleException($request, $e);
        }
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
        return (new ResponseFactory)->createJsonResponse(400, [
            'status' => 'error',
            'message' => $exception->getMessage(),
            'violations' => $exception->getViolations(),
        ]);
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
        return (new ResponseFactory)->createJsonResponse(405, [
            'status' => 'error',
            'message' => $exception->getMessage(),
        ])->withHeader('Allow', implode(',', $exception->getAllowedMethods()));
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
        return (new ResponseFactory)->createJsonResponse(404, [
            'status' => 'error',
            'message' => $exception->getMessage(),
        ]);
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
        return (new ResponseFactory)->createJsonResponse(415, [
            'status' => 'error',
            'message' => $exception->getMessage(),
        ])->withHeader('Accept', implode(',', $exception->getSupportedTypes()));
    }

    /**
     * @param ServerRequestInterface $request
     * @param Throwable $exception
     *
     * @return ResponseInterface
     *
     * @link https://github.com/filp/whoops
     */
    private function handleException(
        ServerRequestInterface $request,
        Throwable $exception
    ) : ResponseInterface {
        $this->container->get('logger')->error($exception->getMessage(), [
            'exception' => $exception,
        ]);

        if (false === stripos($request->getHeaderLine('Accept'), 'text/html')) {
            return (new ResponseFactory)->createResponse(500);
        }

        $whoops = new Whoops();
        $whoops->allowQuit(false);
        $whoops->sendHttpCode(false);
        $whoops->writeToOutput(false);
        $whoops->pushHandler(new PrettyPageHandler());

        $response = (new ResponseFactory)->createResponse(500)
            ->withHeader('Content-Type', 'text/html');

        $response->getBody()->write($whoops->handleException($exception));

        return $response;
    }
}
