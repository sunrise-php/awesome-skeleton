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
 *   name="api.entry.read",
 *   path="/api/v1/entry/{id<\d+>}",
 *   methods={"GET"},
 * )
 *
 * @OpenApi\Operation(
 *   tags={"Entry"},
 *   summary="Read an entry",
 *   responses={
 *     200: @OpenApi\Response(
 *       description="OK",
 *       content={
 *         "application/json": @OpenApi\MediaType(
 *           schema=@OpenApi\Schema(
 *             type="object",
 *             required={"status", "data"},
 *             properties={
 *               "status": @OpenApi\SchemaReference(
 *                 class="App\Http\ResponseFactory",
 *                 method="ok",
 *               ),
 *               "data": @OpenApi\SchemaReference(
 *                 class="App\Entity\Entry",
 *               ),
 *             },
 *           ),
 *         ),
 *       },
 *     ),
 *     "default": @OpenApi\ResponseReference(
 *       class="App\Http\ResponseFactory",
 *       method="error",
 *     ),
 *   },
 * )
 */
final class ReadController implements RequestHandlerInterface
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

        return (new ResponseFactory)->ok($service->readById($id), 200);
    }
}
