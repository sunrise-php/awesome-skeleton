<?php declare(strict_types=1);

namespace App\Middleware;

/**
 * Import classes
 */
use App\ContainerAwareTrait;
use Arus\Http\Response\ResponseFactoryAwareTrait;
use Middlewares\Utils\HttpErrorException as InvalidPayloadException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Sunrise\Http\Router\Exception\BadRequestException;
use Sunrise\Http\Router\Exception\MethodNotAllowedException;
use Sunrise\Http\Router\Exception\PageNotFoundException;
use Sunrise\Http\Router\Exception\UnsupportedMediaTypeException;
use Throwable;

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
        } catch (InvalidPayloadException $e) {
            return $this->handleInvalidPayload($request, $e);
        } catch (PageNotFoundException $e) {
            return $this->handlePageNotFound($request, $e);
        } catch (MethodNotAllowedException $e) {
            return $this->handleMethodNotAllowed($request, $e);
        } catch (UnsupportedMediaTypeException $e) {
            return $this->handleUnsupportedMediaType($request, $e);
        } catch (Throwable $e) {
            return $this->handleUnexpectedError($request, $e);
        }
    }

    /**
     * @param ServerRequestInterface $request
     * @param BadRequestException $e
     *
     * @return ResponseInterface
     */
    private function handleBadRequest(
        ServerRequestInterface $request,
        BadRequestException $e
    ) : ResponseInterface {
        return $this->jsonViolations($e->getViolations(), 400);
    }

    /**
     * @param ServerRequestInterface $request
     * @param InvalidPayloadException $e
     *
     * @return ResponseInterface
     */
    private function handleInvalidPayload(
        ServerRequestInterface $request,
        InvalidPayloadException $e
    ) : ResponseInterface {
        return $this->error('Invalid payload', 'requestBody', null, 400);
    }

    /**
     * @param ServerRequestInterface $request
     * @param PageNotFoundException $e
     *
     * @return ResponseInterface
     */
    private function handlePageNotFound(
        ServerRequestInterface $request,
        PageNotFoundException $e
    ) : ResponseInterface {
        return $this->createResponse(404);
    }

    /**
     * @param ServerRequestInterface $request
     * @param MethodNotAllowedException $e
     *
     * @return ResponseInterface
     */
    private function handleMethodNotAllowed(
        ServerRequestInterface $request,
        MethodNotAllowedException $e
    ) : ResponseInterface {
        return $this->createResponse(405)
            ->withHeader('Allow', $e->getJoinedAllowedMethods());
    }

    /**
     * @param ServerRequestInterface $request
     * @param UnsupportedMediaTypeException $e
     *
     * @return ResponseInterface
     */
    private function handleUnsupportedMediaType(
        ServerRequestInterface $request,
        UnsupportedMediaTypeException $e
    ) : ResponseInterface {
        return $this->error($e->getMessage(), 'requestBody', null, 415)
            ->withHeader('Accept', $e->getJoinedSupportedTypes());
    }

    /**
     * @param ServerRequestInterface $request
     * @param Throwable $e
     *
     * @return ResponseInterface
     */
    private function handleUnexpectedError(
        ServerRequestInterface $request,
        Throwable $e
    ) : ResponseInterface {
        $this->container->get('logger')->error($e->getMessage(), [
            'exception' => $e,
        ]);

        if ($this->container->get('app.silent')) {
            return $this->createResponse(500);
        }

        return $this->error(
            $e->getMessage(),
            $e->getFile() .':'. $e->getLine(),
            $e->getCode(),
            500
        );
    }
}
