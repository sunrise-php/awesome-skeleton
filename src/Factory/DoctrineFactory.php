<?php declare(strict_types=1);

namespace App\Factory;

/**
 * Import classes
 */
use Arus\Doctrine\RepositoryFactory\InjectableRepositoryFactory;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;

/**
 * DoctrineFactory
 */
class DoctrineFactory
{

    /**
     * Creates Doctrine Entity Manager instance
     *
     * @param array $params
     * @param ContainerInterface $container
     *
     * @return EntityManagerInterface
     */
    public function createEntityManager(array $params, ContainerInterface $container) : EntityManagerInterface
    {
        $cache = $params['cache'] ?? new ArrayCache();

        $configuration = Setup::createConfiguration(false, $params['proxyDir'], $cache);

        $configuration->setMetadataDriverImpl(
            $configuration->newDefaultAnnotationDriver($params['metadata']['sources'], true)
        );

        $configuration->setRepositoryFactory(
            new InjectableRepositoryFactory($container)
        );

        return EntityManager::create($params['connection'], $configuration);
    }
}
