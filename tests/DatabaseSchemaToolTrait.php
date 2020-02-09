<?php declare(strict_types=1);

namespace App\Tests;

/**
 * Import classes
 */
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;

/**
 * DatabaseSchemaToolTrait
 */
trait DatabaseSchemaToolTrait
{

    /**
     * Creates a database schema associated with the given entity manager
     *
     * @param EntityManagerInterface $entityManager
     *
     * @return void
     */
    private function createDatabaseSchema(EntityManagerInterface $entityManager) : void
    {
        $schema = new SchemaTool($entityManager);

        $schema->dropSchema(
            $entityManager->getMetadataFactory()->getAllMetadata()
        );

        $schema->createSchema(
            $entityManager->getMetadataFactory()->getAllMetadata()
        );
    }
}
