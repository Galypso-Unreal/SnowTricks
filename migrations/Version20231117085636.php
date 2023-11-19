<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231117085636 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE st_trick_group (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE st_trick ADD trick_group_id INT NOT NULL');
        $this->addSql('ALTER TABLE st_trick ADD CONSTRAINT FK_BCD059039B875DF8 FOREIGN KEY (trick_group_id) REFERENCES st_trick_group (id)');
        $this->addSql('CREATE INDEX IDX_BCD059039B875DF8 ON st_trick (trick_group_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE st_trick DROP FOREIGN KEY FK_BCD059039B875DF8');
        $this->addSql('DROP TABLE st_trick_group');
        $this->addSql('DROP INDEX IDX_BCD059039B875DF8 ON st_trick');
        $this->addSql('ALTER TABLE st_trick DROP trick_group_id');
    }
}
