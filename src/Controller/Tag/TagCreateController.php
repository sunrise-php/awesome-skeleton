<?php declare(strict_types=1);

namespace App\Controller\Tag;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Sunrise\Http\Message\ResponseFactory;

/**
 * @Route(
 *   name="tag.create",
 *   path="/tag",
 *   methods={"POST"},
 *   middlewares={
 *     "App\Middleware\RequestBodyValidationMiddleware",
 *   },
 * )
 *
 * @OpenApi\Operation(
 *   summary="Create a tag",
 *   requestBody=@OpenApi\RequestBody(
 *     content={
 *       "application/json": @OpenApi\MediaType(
 *         schema=@OpenApi\SchemaReference(
 *           class="App\Service\TagService",
 *           method="create",
 *         ),
 *       ),
 *       "application/x-www-form-urlencoded": @OpenApi\MediaType(
 *         schema=@OpenApi\SchemaReference(
 *           class="App\Service\TagService",
 *           method="create",
 *         ),
 *       ),
 *     },
 *   ),
 *   responses={
 *     201: @OpenApi\Response(
 *       description="Created",
 *       content={
 *         "application/json": @OpenApi\MediaType(
 *           schema=@OpenApi\Schema(
 *             type="object",
 *             properties={
 *               "status": @OpenApi\Schema(
 *                 type="string",
 *                 enum={"ok"},
 *               ),
 *             },
 *           ),
 *         ),
 *       },
 *     ),
 *   },
 * )
 */
final class TagCreateController implements RequestHandlerInterface
{

    /**
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        return (new ResponseFactory)->createJsonResponse(201, [
            'status' => 'ok',
        ]);
    }
}
