<?php declare(strict_types=1);

namespace App\Controller\Tag;

use App\Service\TagService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Sunrise\Http\Message\ResponseFactory;

/**
 * @Route(
 *   name="tag.list",
 *   path="/tag",
 *   methods={"GET"},
 * )
 *
 * @OpenApi\Operation(
 *   summary="Tag list",
 *   responses={
 *     200: @OpenApi\Response(
 *       description="OK",
 *       content={
 *         "application/json": @OpenApi\MediaType(
 *           schema=@OpenApi\Schema(
 *             type="object",
 *             required={
 *               "status",
 *               "data",
 *             },
 *             properties={
 *               "status": @OpenApi\Schema(
 *                 type="string",
 *                 enum={"ok"},
 *               ),
 *               "data": @OpenApi\SchemaReference(
 *                 class="App\Service\TagService",
 *                 method="list",
 *               ),
 *             },
 *           ),
 *         ),
 *       },
 *     ),
 *   },
 * )
 */
final class TagListController implements RequestHandlerInterface
{

    /**
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        return (new ResponseFactory)->createJsonResponse(200, [
            'status' => 'ok',
            'data' => (new TagService)->list(),
        ])->withHeader('Access-Control-Allow-Origin', '*');
    }
}
