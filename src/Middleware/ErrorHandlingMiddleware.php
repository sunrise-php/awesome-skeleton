<?php declare(strict_types=1);

namespace App\Middleware;

/**
 * Import classes
 */
use App\ContainerAwareTrait;
use App\Exception\InvalidEntityException;
use Arus\Http\Response\ResponseFactoryAwareTrait;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Sunrise\Http\Router\Exception\BadRequestException;
use Sunrise\Http\Router\Exception\MethodNotAllowedException;
use Sunrise\Http\Router\Exception\RouteNotFoundException;
use Sunrise\Http\Router\Exception\UnsupportedMediaTypeException;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Whoops\Run as Whoops;
use Whoops\Handler\PrettyPageHandler;
use Throwable;

/**
 * Import functions
 */
use function get_class;
use function implode;
use function preg_quote;
use function preg_replace;
use function sprintf;

/**
 * ErrorHandlingMiddleware
 */
final class ErrorHandlingMiddleware implements MiddlewareInterface
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
        } catch (InvalidEntityException $e) {
            return $this->handleInvalidEntity($request, $e);
        } catch (Throwable $e) {
            return $this->handleException($request, $e);
        }
    }

    /**
     * Returns a response with the given processed exception
     *
     * @param ServerRequestInterface $request
     * @param BadRequestException $exception
     *
     * @return ResponseInterface
     */
    private function handleBadRequest(
        ServerRequestInterface $request,
        BadRequestException $exception
    ) : ResponseInterface {
        $violations = new ConstraintViolationList();

        foreach ($exception->getViolations() as $v) {
            $violations->add(new ConstraintViolation(
                $v['message'],
                null,
                [],
                null,
                $v['property'],
                null,
                null,
                'b187c971-810b-455a-baf3-06dc6a1591f4',
                null,
                null
            ));
        }

        return $this->violations($violations, 400);
    }

    /**
     * Returns a response with the given processed exception
     *
     * @param ServerRequestInterface $request
     * @param MethodNotAllowedException $exception
     *
     * @return ResponseInterface
     */
    private function handleMethodNotAllowed(
        ServerRequestInterface $request,
        MethodNotAllowedException $exception
    ) : ResponseInterface {
        return $this->error(
            $exception->getMessage(),
            $request->getUri()->getPath(),
            '7d8f78d7-c689-409b-8031-8401ab5836b6',
            405
        )->withHeader('Allow', implode(',', $exception->getAllowedMethods()));
    }

    /**
     * Returns a response with the given processed exception
     *
     * @param ServerRequestInterface $request
     * @param RouteNotFoundException $exception
     *
     * @return ResponseInterface
     */
    private function handleRouteNotFound(
        ServerRequestInterface $request,
        RouteNotFoundException $exception
    ) : ResponseInterface {
        return $this->error(
            $exception->getMessage(),
            $request->getUri()->getPath(),
            '979775e6-a43b-414f-bb72-cbe0133f621e',
            404
        );
    }

    /**
     * Returns a response with the given processed exception
     *
     * @param ServerRequestInterface $request
     * @param UnsupportedMediaTypeException $exception
     *
     * @return ResponseInterface
     */
    private function handleUnsupportedMediaType(
        ServerRequestInterface $request,
        UnsupportedMediaTypeException $exception
    ) : ResponseInterface {
        return $this->error(
            $exception->getMessage(),
            $request->getUri()->getPath(),
            '87255179-5041-4f1b-a469-b891ad5dc623',
            415
        )->withHeader('Accept', implode(',', $exception->getSupportedTypes()));
    }

    /**
     * Returns a response with the given processed exception
     *
     * @param ServerRequestInterface $request
     * @param InvalidEntityException $exception
     *
     * @return ResponseInterface
     */
    private function handleInvalidEntity(
        ServerRequestInterface $request,
        InvalidEntityException $exception
    ) : ResponseInterface {
        return $this->violations($exception->getEntityViolations(), 400);
    }

    /**
     * Returns a response with the given processed exception
     *
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
            return $this->error(
                sprintf(
                    'Caught the exception %s in the file %s on line %d.',
                    get_class($exception),
                    $this->hideRoot($exception->getFile()),
                    $exception->getLine()
                ),
                $request->getUri()->getPath(),
                '594358d2-b5f1-4cfc-8c60-df43cfd720b3',
                500
            );
        }

        $whoops = new Whoops();
        $whoops->allowQuit(false);
        $whoops->sendHttpCode(false);
        $whoops->writeToOutput(false);
        $whoops->pushHandler(new PrettyPageHandler());

        return $this->html($whoops->handleException($exception), 500);
    }

    /**
     * Hides the application root from the given path
     *
     * @param string $path
     *
     * @return string
     */
    private function hideRoot(string $path) : string
    {
        $root = preg_quote($this->container->get('app.root'), '/');
        $path = preg_replace('/^' . $root . '/ui', '', $path);

        return $path;
    }
}
