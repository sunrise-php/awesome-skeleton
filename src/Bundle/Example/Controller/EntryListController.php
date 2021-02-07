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
 *   name="api_v1_entry_list",
 *   path="/api/v1/entry",
 *   methods={"GET"},
 *   summary="Gets a list of entries ",
 *   tags={"entry"},
 *   middlewares={
 *     "App\Middleware\RequestQueryValidationMiddleware",
 *   },
 * )
 *
 * @OpenApi\Operation(
 *   parameters={
 *     @OpenApi\Parameter(
 *       in="query",
 *       name="limit",
 *       required=false,
 *       schema=@OpenApi\Schema(
 *         type="string",
 *         pattern="^(?:[1-9][0-9]*)?$",
 *       ),
 *     ),
 *     @OpenApi\Parameter(
 *       in="query",
 *       name="offset",
 *       required=false,
 *       schema=@OpenApi\Schema(
 *         type="string",
 *         pattern="^(?:0|[1-9][0-9]*)?$",
 *       ),
 *     ),
 *   },
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
 *                 method="serializeList",
 *               ),
 *             },
 *           ),
 *         ),
 *       },
 *     ),
 *   },
 * )
 */
final class EntryListController implements RequestHandlerInterface
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
        $q = $request->getQueryParams();

        $limit = 10;
        $offset = 0;

        if (!empty($q['limit'])) {
            $limit = (int) $q['limit'];
        }

        if (!empty($q['offset'])) {
            $offset = (int) $q['offset'];
        }

        $entries = $this->container->get('entryManager')->getList($limit, $offset);
        $data = $this->container->get('entrySerializer')->serializeList(...$entries);

        return $this->ok($data);
    }
}
