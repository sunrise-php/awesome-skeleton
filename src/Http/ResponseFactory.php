<?php declare(strict_types=1);

namespace App\Http;

/**
 * Import classes
 */
use Psr\Http\Message\ResponseInterface;
use Sunrise\Http\Message\ResponseFactory as BaseResponseFactory;

/**
 * ResponseFactory
 */
final class ResponseFactory extends BaseResponseFactory
{

    /**
     * Returns HTML response
     *
     * @param string $body
     * @param int $status
     *
     * @return ResponseInterface
     */
    public function html(string $body, int $status = 200) : ResponseInterface
    {
        $response = $this->createResponse($status)
            ->withHeader('Content-Type', 'text/html; charset=UTF-8');

        $response->getBody()->write($body);

        return $response;
    }

    /**
     * Returns JSON response
     *
     * @param mixed $data
     * @param int $status
     *
     * @return ResponseInterface
     */
    public function json($data, int $status = 200) : ResponseInterface
    {
        return $this->createJsonResponse($status, $data);
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
    public function ok($data, int $status = 200) : ResponseInterface
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
     *       class="App\Http\ResponseFactory",
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
     *         class="App\Http\ResponseFactory",
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
    public function emptyOk(int $status = 200) : ResponseInterface
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
     *         class="App\Http\ResponseFactory",
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
    public function error(string $message, array $errors = [], int $status = 500) : ResponseInterface
    {
        return $this->json([
            'status' => 'error',
            'message' => $message,
            'errors' => $errors,
        ], $status);
    }
}
