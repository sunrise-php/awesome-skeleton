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
 *   name="api_v1_entry_read",
 *   path="/api/v1/entry/{entryId<@uuid>}",
 *   methods={"GET"},
 *   summary="Reads an existing entry",
 *   tags={"entry"},
 * )
 *
 * @OpenApi\Operation(
 *   responses={
 *     200: @OpenApi\Response(
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
final class EntryReadController implements RequestHandlerInterface
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
        $entry = $this->container->get('entryManager')->findById($request->getAttribute('entryId'));

        return $this->ok($this->container->get('entrySerializer')->serialize($entry));
    }
}
