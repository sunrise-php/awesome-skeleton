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
final class MultipleCreateController extends AbstractRequestHandler implements RequestHandlerInterface
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
        $service = $this->container->get('service.entry');

        $service->multipleCreate(...$request->getParsedBody());

        return $this->emptyOk(201);
    }
}
