<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231215143746 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE st_comment ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE st_comment ADD CONSTRAINT FK_D6A5A84CA76ED395 FOREIGN KEY (user_id) REFERENCES st_user (id)');
        $this->addSql('CREATE INDEX IDX_D6A5A84CA76ED395 ON st_comment (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE st_comment DROP FOREIGN KEY FK_D6A5A84CA76ED395');
        $this->addSql('DROP INDEX IDX_D6A5A84CA76ED395 ON st_comment');
        $this->addSql('ALTER TABLE st_comment DROP user_id');
    }
}
