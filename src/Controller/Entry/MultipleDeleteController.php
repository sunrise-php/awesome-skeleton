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
 *   name="api.entry.multiple.delete",
 *   path="/api/v1/entry",
 *   methods={"DELETE"},
 *   middlewares={
 *     "App\Middleware\RequestBodyValidationMiddleware",
 *   },
 * )
 *
 * @OpenApi\Operation(
 *   tags={"Entry", "Multiple Entry"},
 *   summary="Multiple deletion of entries",
 *   requestBody=@OpenApi\RequestBody(
 *     content={
 *       "application/json": @OpenApi\MediaType(
 *         schema=@OpenApi\Schema(
 *           type="array",
 *           minItems=1,
 *           items=@OpenApi\SchemaReference(
 *             class="App\Entity\Entry",
 *             property="id",
 *           ),
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
final class MultipleDeleteController extends AbstractRequestHandler implements RequestHandlerInterface
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

        $service->multipleDelete(...$request->getParsedBody());

        return $this->emptyOk(200);
    }
}
