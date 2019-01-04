<?php declare(strict_types=1);

namespace App\Http\Controller;

/**
 * Import classes
 */
use Psr\Log\LoggerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * @Route(
 *   id="home",
 *   path="/",
 *   methods={"GET"}
 * )
 */
class HomeController extends AbstractController implements MiddlewareInterface
{

	/**
	 * @Inject
	 *
	 * @var LoggerInterface
	 */
	protected $logger;

	/**
	 * @param ServerRequestInterface $request
	 * @param RequestHandlerInterface $handler
	 *
	 * @return ResponseInterface
	 */
	public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
	{
		$this->logger->debug(__METHOD__);

		$response = $handler->handle($request);

		$response->getBody()->write('Welcome');

		return $response;
	}
}
