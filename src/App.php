<?php declare(strict_types=1);

namespace App;

/**
 * Import classes
 */
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Sunrise\Http\Factory\ResponseFactory;
use Sunrise\Http\Router\Exception\MethodNotAllowedException;
use Sunrise\Http\Router\Exception\RouteNotFoundException;
use Sunrise\Http\Router\RouterInterface;

/**
 * App
 */
class App
{

	/**
	 * The application router
	 *
	 * @var RouterInterface
	 *
	 * @Inject
	 */
	protected $router;

	/**
	 * Runs the application
	 *
	 * @param ServerRequestInterface $request
	 *
	 * @return void
	 */
	public function run(ServerRequestInterface $request) : void
	{
		try
		{
			$response = $this->router->handle($request);
		}
		catch (MethodNotAllowedException $e)
		{
			$response = (new ResponseFactory)->createResponse(405)
			->withHeader('allow', implode(', ', $e->getAllowedMethods()));

			$response->getBody()->write($response->getReasonPhrase());
		}
		catch (RouteNotFoundException $e)
		{
			$response = (new ResponseFactory)->createResponse(404);

			$response->getBody()->write($response->getReasonPhrase());
		}

		$this->emit($response);
	}

	/**
	 * Emits the given response
	 *
	 * @param ResponseInterface $response
	 *
	 * @return void
	 */
	public function emit(ResponseInterface $response) : void
	{
		$headers = $response->getHeaders();

		foreach ($headers as $name => $values)
		{
			foreach ($values as $value)
			{
				\header(\sprintf('%s: %s', $name, $value), false);
			}
		}

		\header(\sprintf('HTTP/%s %d %s',
			$response->getProtocolVersion(),
			$response->getStatusCode(),
			$response->getReasonPhrase()
		), true);

		echo $response->getBody();
	}
}
