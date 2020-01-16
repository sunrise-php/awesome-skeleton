<?php declare(strict_types=1);

namespace App\Controller\Entry;

/**
 * Import classes
 */
use App\ContainerAwareTrait;
use App\Http\ResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * @Route(
 *   name="api.entry.delete",
 *   path="/api/v1/entry/{id<\d+>}",
 *   methods={"DELETE"},
 * )
 *
 * @OpenApi\Operation(
 *   tags={"Entry"},
 *   summary="Delete an entry",
 *   responses={
 *     200: @OpenApi\ResponseReference(
 *       class="App\Http\ResponseFactory",
 *       method="emptyOk",
 *     ),
 *     "default": @OpenApi\ResponseReference(
 *       class="App\Http\ResponseFactory",
 *       method="error",
 *     ),
 *   },
 * )
 */
final class DeleteController implements RequestHandlerInterface
{
    use ContainerAwareTrait;

    /**
     * {@inheritDoc}
     *
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $id = (int) $request->getAttribute('id');

        $service = $this->container->get('service.entry');

        if (!$service->existsById($id)) {
            return (new ResponseFactory)->error('The requested entry was not found.', [], 404);
        }

        $service->deleteById($id, $request->getParsedBody());

        return (new ResponseFactory)->emptyOk(200);
    }
}
