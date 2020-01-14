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
     * @param string $body
     * @param int $status
     *
     * @return ResponseInterface
     */
    final protected function html(string $body, int $status = 200) : ResponseInterface
    {
        $response = (new ResponseFactory)->createResponse($status)
            ->withHeader('Content-Type', 'text/html; charset=UTF-8');

        $response->getBody()->write($body);

        return $response;
    }

    /**
     * Creates HTML response with a rendered view
     *
     * @param string $name
     * @param array $context
     * @param int $status
     *
     * @return ResponseInterface
     */
    final protected function view(string $name, array $context = [], int $status = 200) : ResponseInterface
    {
        return $this->html($this->container->get('twig')->render($name, $context), $status);
    }

    /**
     * Creates JSON response with the given data
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
     * Creates JSON response with the given data
     *
     * @OpenApi\Schema(
     *   refName="StatusOk",
     *   type="string",
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
     * Creates JSON response with the given data
     *
     * @OpenApi\Schema(
     *   refName="StatusError",
     *   type="string",
     *   enum={"error"},
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
}
