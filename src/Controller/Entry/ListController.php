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
 *                 class="App\Http\AbstractRequestHandler",
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
 *       class="App\Http\AbstractRequestHandler",
 *       method="error",
 *     ),
 *   },
 * )
 */
final class ListController extends AbstractRequestHandler implements RequestHandlerInterface
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

        return $this->ok($service->getAll(), 200);
    }
}
