<?php declare(strict_types=1);

namespace App\Controller;

/**
 * Import classes
 */
use App\ContainerAwareTrait;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Sunrise\Http\Message\ResponseFactory;

/**
 * @Route(
 *   name="home",
 *   path="/",
 *   methods={"get"}
 * )
 *
 * @OpenApi\Operation(
 *   summary="Home page",
 *   responses={
 *     200: @OpenApi\Response(
 *       description="All Okay",
 *       content={
 *         "application/json": @OpenApi\MediaType(
 *         schema=@OpenApi\Schema(
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
class HomeController implements RequestHandlerInterface
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
        return (new ResponseFactory)->createJsonResponse(200, [
            'status' => 'ok',
        ]);
    }
}
