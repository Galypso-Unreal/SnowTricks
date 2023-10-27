<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231027075343 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE st_video (id INT AUTO_INCREMENT NOT NULL, trick_id INT DEFAULT NULL, embed LONGTEXT NOT NULL, INDEX IDX_18E72A31B281BE2E (trick_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE st_video ADD CONSTRAINT FK_18E72A31B281BE2E FOREIGN KEY (trick_id) REFERENCES st_trick (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE st_video DROP FOREIGN KEY FK_18E72A31B281BE2E');
        $this->addSql('DROP TABLE st_video');
    }
}
