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
use Whoops\Run as WhoopsRun;
use Whoops\Handler\PrettyPageHandler as WhoopsPrettyPageHandler;
use Throwable;

/**
 * Import functions
 */
use function implode;

/**
 * ErrorHandlingMiddleware
 */
class ErrorHandlingMiddleware implements MiddlewareInterface
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
        } catch (BadRequestException $error) {
            return $this->handleBadRequest($request, $error);
        } catch (MethodNotAllowedException $error) {
            return $this->handleMethodNotAllowed($request, $error);
        } catch (RouteNotFoundException $error) {
            return $this->handleRouteNotFound($request, $error);
        } catch (UnsupportedMediaTypeException $error) {
            return $this->handleUnsupportedMediaType($request, $error);
        } catch (Throwable $error) {
            return $this->handleException($request, $error);
        }
    }

    /**
     * @param ServerRequestInterface $request
     * @param BadRequestException $error
     *
     * @return ResponseInterface
     */
    private function handleBadRequest(
        ServerRequestInterface $request,
        BadRequestException $error
    ) : ResponseInterface {
        return (new ResponseFactory)->createJsonResponse(400, [
            'status' => 'error',
            'message' => $error->getMessage(),
            'violations' => $error->getViolations(),
        ]);
    }

    /**
     * @param ServerRequestInterface $request
     * @param MethodNotAllowedException $error
     *
     * @return ResponseInterface
     */
    private function handleMethodNotAllowed(
        ServerRequestInterface $request,
        MethodNotAllowedException $error
    ) : ResponseInterface {
        return (new ResponseFactory)->createJsonResponse(405, [
            'status' => 'error',
            'message' => $error->getMessage(),
        ])->withHeader('Allow', implode(',', $error->getAllowedMethods()));
    }

    /**
     * @param ServerRequestInterface $request
     * @param RouteNotFoundException $error
     *
     * @return ResponseInterface
     */
    private function handleRouteNotFound(
        ServerRequestInterface $request,
        RouteNotFoundException $error
    ) : ResponseInterface {
        return (new ResponseFactory)->createJsonResponse(404, [
            'status' => 'error',
            'message' => $error->getMessage(),
        ]);
    }

    /**
     * @param ServerRequestInterface $request
     * @param UnsupportedMediaTypeException $error
     *
     * @return ResponseInterface
     */
    private function handleUnsupportedMediaType(
        ServerRequestInterface $request,
        UnsupportedMediaTypeException $error
    ) : ResponseInterface {
        return (new ResponseFactory)->createJsonResponse(415, [
            'status' => 'error',
            'message' => $error->getMessage(),
        ])->withHeader('Accept', implode(',', $error->getSupportedTypes()));
    }

    /**
     * @param ServerRequestInterface $request
     * @param Throwable $error
     *
     * @return ResponseInterface
     */
    private function handleException(
        ServerRequestInterface $request,
        Throwable $error
    ) : ResponseInterface {
        $this->container->get('logger')->error($error->getMessage(), [
            'exception' => $error,
        ]);

        $whoops = new WhoopsRun();
        $whoops->allowQuit(false);
        $whoops->sendHttpCode(false);
        $whoops->writeToOutput(false);
        $whoops->pushHandler(new WhoopsPrettyPageHandler());

        $response = (new ResponseFactory)->createResponse(500)
            ->withHeader('Content-Type', 'text/html');

        $response->getBody()->write(
            $whoops->handleException($error)
        );

        return $response;
    }
}
