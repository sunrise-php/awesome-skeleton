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
 *   name="api.entry.list",
 *   path="/api/v1/entry",
 *   methods={"GET"},
 * )
 *
 * @OpenApi\Operation(
 *   tags={"Entry"},
 *   summary="Entries list",
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
 *               "data": @OpenApi\Schema(
 *                 type="array",
 *                 items=@OpenApi\SchemaReference(
 *                   class="App\Entity\Entry",
 *                 ),
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
final class ListController implements RequestHandlerInterface
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

        return (new ResponseFactory)->ok($service->getAll(), 200);
    }
}
