<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210203204551 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('CREATE TABLE Entry (
            id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\',
            name VARCHAR(128) NOT NULL,
            slug VARCHAR(128) NOT NULL,
            createdAt DATETIME NOT NULL,
            updatedAt DATETIME DEFAULT NULL,
            INDEX IDX_EAE0B27486CA693C (createdAt),
            UNIQUE INDEX UNIQ_EAE0B274989D9B62 (slug),
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('DROP TABLE Entry');
    }
}
