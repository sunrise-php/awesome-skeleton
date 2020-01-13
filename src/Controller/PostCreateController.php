<?php declare(strict_types=1);

namespace App\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Sunrise\Http\Message\ResponseFactory;

/**
 * @Route(
 *   name="post.create",
 *   path="/post",
 *   methods={"post", "put"},
 *   middlewares={
 *     "App\Middleware\RequestBodyValidationMiddleware",
 *   },
 * )
 *
 * @OpenApi\RequestBody(
 *   content={
 *     "application/json": @OpenApi\MediaType(
 *       schema=@OpenApi\SchemaReference(
 *         class="App\Service\PostService",
 *         method="createPost",
 *       ),
 *     ),
 *     "application/x-www-form-urlencoded": @OpenApi\MediaType(
 *       schema=@OpenApi\SchemaReference(
 *         class="App\Service\PostService",
 *         method="createPost",
 *       ),
 *     ),
 *   },
 * )
 *
 * @OpenApi\Operation(
 *   summary="Create a post",
 *   requestBody=@OpenApi\RequestBodyReference(
 *     class="App\Controller\PostCreateController",
 *   ),
 *   responses={
 *     201: @OpenApi\Response(
 *       description="OK",
 *     ),
 *   },
 * )
 */
final class PostCreateController implements RequestHandlerInterface
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
