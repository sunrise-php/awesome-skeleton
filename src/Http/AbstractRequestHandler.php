<?php declare(strict_types=1);

namespace App\Http;

/**
 * Import classes
 */
use App\ContainerAwareTrait;
use Psr\Http\Message\ResponseInterface;
use Sunrise\Http\Message\ResponseFactory;

/**
 * AbstractRequestHandler
 */
abstract class AbstractRequestHandler
{
    use ContainerAwareTrait;

    /**
     * Returns empty response
     *
     * @param int $status
     *
     * @return ResponseInterface
     */
    final protected function empty(int $status = 200) : ResponseInterface
    {
        return (new ResponseFactory)->createResponse($status);
    }

    /**
     * Returns JSON response
     *
     * @param mixed $data
     * @param int $status
     *
     * @return ResponseInterface
     */
    final protected function json($data, int $status = 200) : ResponseInterface
    {
        return (new ResponseFactory)->createJsonResponse($status, $data);
    }

    /**
     * Returns JSON response to inform the client about success
     *
     * @OpenApi\Schema(
     *   refName="StatusOk",
     *   type="string",
     *   nullable=false,
     *   pattern="^ok$",
     * )
     *
     * @param mixed $data
     * @param int $status
     *
     * @return ResponseInterface
     */
    final protected function ok($data, int $status = 200) : ResponseInterface
    {
        return $this->json([
            'status' => 'ok',
            'data' => $data,
        ], $status);
    }

    /**
     * Returns empty JSON response to inform the client about success
     *
     * @OpenApi\Schema(
     *   refName="FullEmptyStatusOk",
     *   type="object",
     *   required={"status", "data"},
     *   properties={
     *     "status": @OpenApi\SchemaReference(
     *       class="App\Http\AbstractRequestHandler",
     *       method="ok",
     *     ),
     *     "data": @OpenApi\Schema(
     *       type="array",
     *       items=@OpenApi\Schema(),
     *     ),
     *   },
     * )
     *
     * @OpenApi\Response(
     *   refName="ResponseEmptyOk",
     *   description="OK",
     *   content={
     *     "application/json": @OpenApi\MediaType(
     *       schema=@OpenApi\SchemaReference(
     *         class="App\Http\AbstractRequestHandler",
     *         method="emptyOk",
     *       ),
     *     ),
     *   },
     * )
     *
     * @param int $status
     *
     * @return ResponseInterface
     */
    final protected function emptyOk(int $status = 200) : ResponseInterface
    {
        return $this->ok([], $status);
    }

    /**
     * Returns JSON response to inform the client about error
     *
     * @OpenApi\Schema(
     *   refName="FullStatusError",
     *   type="object",
     *   required={"status", "message", "errors"},
     *   properties={
     *     "status": @OpenApi\Schema(
     *       type="string",
     *       nullable=false,
     *       pattern="^error$",
     *     ),
     *     "message": @OpenApi\Schema(
     *       type="string",
     *       nullable=false,
     *     ),
     *     "errors": @OpenApi\Schema(
     *       type="array",
     *       items=@OpenApi\Schema(
     *         type="object",
     *         required={"property", "message"},
     *         properties={
     *           "property": @OpenApi\Schema(
     *             type="string",
     *             nullable=false,
     *           ),
     *           "message": @OpenApi\Schema(
     *             type="string",
     *             nullable=false,
     *           ),
     *         },
     *       ),
     *     ),
     *   },
     * )
     *
     * @OpenApi\Response(
     *   refName="ResponseError",
     *   description="Some error",
     *   content={
     *     "application/json": @OpenApi\MediaType(
     *       schema=@OpenApi\SchemaReference(
     *         class="App\Http\AbstractRequestHandler",
     *         method="error",
     *       ),
     *     ),
     *   },
     * )
     *
     * @param string $message
     * @param array $errors
     * @param int $status
     *
     * @return ResponseInterface
     */
    final protected function error(string $message, array $errors = [], int $status = 500) : ResponseInterface
    {
        return $this->json([
            'status' => 'error',
            'message' => $message,
            'errors' => $errors,
        ], $status);
    }

    /**
     * Returns HTML response
     *
     * @param string $body
     * @param int $status
     *
     * @return ResponseInterface
     */
    final protected function html(string $body, int $status = 200) : ResponseInterface
    {
        $response = $this->empty($status)
            ->withHeader('Content-Type', 'text/html; charset=UTF-8');

        $response->getBody()->write($body);

        return $response;
    }

    /**
     * Returns HTML response with rendered view
     *
     * @param string $name
     * @param array $context
     * @param int $status
     *
     * @return ResponseInterface
     */
    final protected function view(string $name, array $context = [], int $status = 200) : ResponseInterface
    {
        $twig = $this->container->get('twig');
        $body = $twig->render($name, $context);

        return $this->html($body, $status);
    }
}
