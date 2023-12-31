<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231130140322 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE st_trick ADD CONSTRAINT FK_BCD059039B875DF8 FOREIGN KEY (trick_group_id) REFERENCES st_trick_group (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BCD059035E237E06 ON st_trick (name)');
        $this->addSql('CREATE INDEX IDX_BCD059039B875DF8 ON st_trick (trick_group_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE st_trick DROP FOREIGN KEY FK_BCD059039B875DF8');
        $this->addSql('DROP INDEX UNIQ_BCD059035E237E06 ON st_trick');
        $this->addSql('DROP INDEX IDX_BCD059039B875DF8 ON st_trick');
    }
}
