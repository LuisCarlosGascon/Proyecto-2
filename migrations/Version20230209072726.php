<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230209072726 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE distribucion ADD mesa_id INT NOT NULL');
        $this->addSql('ALTER TABLE distribucion ADD CONSTRAINT FK_698658A78BDC7AE9 FOREIGN KEY (mesa_id) REFERENCES mesa (id)');
        $this->addSql('CREATE INDEX IDX_698658A78BDC7AE9 ON distribucion (mesa_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE distribucion DROP FOREIGN KEY FK_698658A78BDC7AE9');
        $this->addSql('DROP INDEX IDX_698658A78BDC7AE9 ON distribucion');
        $this->addSql('ALTER TABLE distribucion DROP mesa_id');
    }
}
