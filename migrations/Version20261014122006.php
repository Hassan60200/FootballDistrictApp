<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261014122006 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE accounts (id CHAR(36) NOT NULL, email VARCHAR(180) NOT NULL, password VARCHAR(255) NOT NULL, active TINYINT NOT NULL, created_at DATETIME NOT NULL, profile_type VARCHAR(20) NOT NULL, profile_identifier VARCHAR(36) NOT NULL, UNIQUE INDEX UNIQ_CAC89EACE7927C74 (email), UNIQUE INDEX uniq_account_profile (profile_type, profile_identifier), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE accounts');
    }
}
