<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231027123641 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_C6CB0B50E7927C74 ON st_user');
        $this->addSql('ALTER TABLE st_user ADD username VARCHAR(180) NOT NULL, DROP firstname, DROP deleted_at, CHANGE email email VARCHAR(255) NOT NULL, CHANGE is_valid is_verified TINYINT(1) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C6CB0B50F85E0677 ON st_user (username)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_C6CB0B50F85E0677 ON st_user');
        $this->addSql('ALTER TABLE st_user ADD firstname VARCHAR(100) NOT NULL, ADD deleted_at DATETIME DEFAULT NULL, DROP username, CHANGE email email VARCHAR(180) NOT NULL, CHANGE is_verified is_valid TINYINT(1) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C6CB0B50E7927C74 ON st_user (email)');
    }
}
