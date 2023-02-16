<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230209083815 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE distribuciones ADD mesa_id INT NOT NULL');
        $this->addSql('ALTER TABLE distribuciones ADD CONSTRAINT FK_14A937F58BDC7AE9 FOREIGN KEY (mesa_id) REFERENCES mesa (id)');
        $this->addSql('CREATE INDEX IDX_14A937F58BDC7AE9 ON distribuciones (mesa_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE distribuciones DROP FOREIGN KEY FK_14A937F58BDC7AE9');
        $this->addSql('DROP INDEX IDX_14A937F58BDC7AE9 ON distribuciones');
        $this->addSql('ALTER TABLE distribuciones DROP mesa_id');
    }
}
