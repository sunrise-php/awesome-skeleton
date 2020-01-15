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
     *   enum={"ok"},
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
     * Returns JSON response to inform the client about error
     *
     * @OpenApi\Schema(
     *   refName="StatusError",
     *   type="object",
     *   properties={
     *     "status": @OpenApi\Schema(
     *       type="string",
     *       nullable=false,
     *       enum={"error"},
     *     ),
     *     "message": @OpenApi\Schema(
     *       type="string",
     *       nullable=false,
     *     ),
     *     "errors": @OpenApi\Schema(
     *       type="array",
     *       items=@OpenApi\Schema(
     *         type="array",
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
