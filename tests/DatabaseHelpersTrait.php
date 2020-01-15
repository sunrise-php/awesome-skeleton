<?php declare(strict_types=1);

namespace App\Tests;

/**
 * Import classes
 */
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;

/**
 * DatabaseHelpersTrait
 */
trait DatabaseHelpersTrait
{

    /**
     * Creates a schema instance for the current database
     *
     * @param EntityManagerInterface $entityManager
     *
     * @return void
     */
    private function createSchema(EntityManagerInterface $entityManager) : void
    {
        $schema = new SchemaTool($entityManager);
        $schema->dropSchema($entityManager->getMetadataFactory()->getAllMetadata());
        $schema->createSchema($entityManager->getMetadataFactory()->getAllMetadata());
    }
}
