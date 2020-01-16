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
 *   name="api.entry.multiple.create",
 *   path="/api/v1/entry",
 *   methods={"PUT"},
 *   middlewares={
 *     "App\Middleware\RequestBodyValidationMiddleware",
 *   },
 * )
 *
 * @OpenApi\Operation(
 *   tags={"Entry", "Multiple Entry"},
 *   summary="Multiple creation of entries",
 *   requestBody=@OpenApi\RequestBody(
 *     content={
 *       "application/json": @OpenApi\MediaType(
 *         schema=@OpenApi\Schema(
 *           type="array",
 *           minItems=1,
 *           items=@OpenApi\Schema(
 *             type="object",
 *             required={"name"},
 *             properties={
 *               "name"=@OpenApi\SchemaReference(
 *                 class="App\Entity\Entry",
 *                 property="name",
 *               ),
 *             },
 *           ),
 *         ),
 *       ),
 *     },
 *   ),
 *   responses={
 *     201: @OpenApi\ResponseReference(
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
final class MultipleCreateController implements RequestHandlerInterface
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
        $service = $this->container->get('service.entry');

        $service->multipleCreate(...$request->getParsedBody());

        return (new ResponseFactory)->emptyOk(201);
    }
}
