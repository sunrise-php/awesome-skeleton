<?php declare(strict_types=1);

namespace App\Middleware;

/**
 * Import classes
 */
use App\Http\AbstractRequestHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
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

/**
 * ErrorHandlingMiddleware
 */
final class ErrorHandlingMiddleware extends AbstractRequestHandler implements MiddlewareInterface
{

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
        return $this->error($exception->getMessage(), $exception->getViolations(), 400);
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
        return $this->error($exception->getMessage(), [], 405)
            ->withHeader('Allow', implode(',', $exception->getAllowedMethods()));
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
        return $this->error($exception->getMessage(), [], 404);
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
        return $this->error($exception->getMessage(), [], 415)
            ->withHeader('Accept', implode(',', $exception->getSupportedTypes()));
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

        if (!$this->container->get('app.display_errors')) {
            return $this->empty(500);
        }

        $whoops = new Whoops();
        $whoops->allowQuit(false);
        $whoops->sendHttpCode(false);
        $whoops->writeToOutput(false);
        $whoops->pushHandler(new PrettyPageHandler());

        return $this->html($whoops->handleException($exception), 500);
    }
}
