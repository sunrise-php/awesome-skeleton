<?php declare(strict_types=1);

namespace App\Controller\Entry;

/**
 * Import classes
 */
use App\Http\AbstractRequestHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * @Route(
 *   name="api.entry.update",
 *   path="/api/v1/entry/{id<\d+>}",
 *   methods={"PATCH"},
 *   middlewares={
 *     "App\Middleware\RequestBodyValidationMiddleware",
 *   },
 * )
 *
 * @OpenApi\Operation(
 *   tags={"Entry"},
 *   summary="Update an entry",
 *   requestBody=@OpenApi\RequestBody(
 *     content={
 *       "application/json": @OpenApi\MediaType(
 *         schema=@OpenApi\Schema(
 *           type="object",
 *           properties={
 *             "name"=@OpenApi\SchemaReference(
 *               class="App\Entity\Entry",
 *               property="name",
 *             ),
 *           },
 *         ),
 *       ),
 *     },
 *   ),
 *   responses={
 *     200: @OpenApi\ResponseReference(
 *       class="App\Http\AbstractRequestHandler",
 *       method="emptyOk",
 *     ),
 *     "default": @OpenApi\ResponseReference(
 *       class="App\Http\AbstractRequestHandler",
 *       method="error",
 *     ),
 *   },
 * )
 */
final class UpdateController extends AbstractRequestHandler implements RequestHandlerInterface
{

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
            return $this->error('The requested entry was not found.', [], 404);
        }

        $service->updateById($id, $request->getParsedBody());

        return $this->emptyOk(200);
    }
}
