<?php declare(strict_types=1);

namespace App\Bundle\Example\Controller;

/**
 * Import classes
 */
use App\ContainerAwareTrait;
use Arus\Http\Response\ResponseFactoryAwareTrait;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * @Route(
 *   name="api_v1_entry_create",
 *   path="/api/v1/entry",
 *   methods={"POST"},
 *   summary="Creates a new entry",
 *   tags={"entry"},
 *   middlewares={
 *     "App\Middleware\RequestBodyValidationMiddleware",
 *   },
 * )
 *
 * @OpenApi\Operation(
 *   requestBody=@OpenApi\RequestBody(
 *     content={
 *       "application/json": @OpenApi\MediaType(
 *         schema=@OpenApi\Schema(
 *           type="object",
 *           properties={
 *             "name": @OpenApi\SchemaReference(
 *               class="App\Bundle\Example\Entity\Entry",
 *               property="name",
 *             ),
 *             "slug": @OpenApi\SchemaReference(
 *               class="App\Bundle\Example\Entity\Entry",
 *               property="slug",
 *             ),
 *           },
 *           required={
 *             "name",
 *             "slug",
 *           },
 *           allowAdditionalProperties=false,
 *         ),
 *       ),
 *     },
 *   ),
 *   responses={
 *     201: @OpenApi\Response(
 *       description="OK",
 *       content={
 *         "application/json": @OpenApi\MediaType(
 *           schema=@OpenApi\Schema(
 *             type="object",
 *             properties={
 *               "data": @OpenApi\SchemaReference(
 *                 class="App\Bundle\Example\Service\EntrySerializer",
 *                 method="serialize",
 *               ),
 *             },
 *           ),
 *         ),
 *       },
 *     ),
 *   },
 * )
 */
final class EntryCreateController implements RequestHandlerInterface
{
    use ContainerAwareTrait;
    use ResponseFactoryAwareTrait;

    /**
     * {@inheritDoc}
     *
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $entry = $this->container->get('entryManager')->create($request->getParsedBody());

        return $this->ok($this->container->get('entrySerializer')->serialize($entry), [], 201);
    }
}
