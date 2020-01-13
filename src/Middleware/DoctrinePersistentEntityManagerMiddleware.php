<?php declare(strict_types=1);

namespace App\Middleware;

/**
 * Import classes
 */
use App\ContainerAwareTrait;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Import constants
 */
use const PHP_SAPI;

/**
 * DoctrinePersistentEntityManagerMiddleware
 *
 * This middleware is for long-running applications only!
 */
final class DoctrinePersistentEntityManagerMiddleware implements MiddlewareInterface
{
    use ContainerAwareTrait;

    /**
     * {@inheritDoc}
     *
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        if ($this->isCli()) {
            $this->container->set('entityManager', $this->reopen(
                $this->container->get('entityManager')
            ));
        }

        return $handler->handle($request);
    }

    /**
     * Checks if the application is running CLI mode
     *
     * @return bool
     */
    private function isCli() : bool
    {
        return 'cli' === PHP_SAPI && 'test' !== $this->container->get('app.env');
    }

    /**
     * Reopens the given entity manager
     *
     * @param EntityManagerInterface $entityManager
     *
     * @return EntityManagerInterface
     */
    private function reopen(EntityManagerInterface $entityManager) : EntityManagerInterface
    {
        if ($entityManager->isOpen()) {
            return $entityManager;
        }

        return EntityManager::create(
            $entityManager->getConnection(),
            $entityManager->getConfiguration(),
            $entityManager->getConnection()->getEventManager()
        );
    }
}
