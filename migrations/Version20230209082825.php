<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230209082825 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE distribucion DROP FOREIGN KEY FK_698658A78BDC7AE9');
        $this->addSql('DROP TABLE distribucion');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE distribucion (id INT AUTO_INCREMENT NOT NULL, mesa_id INT NOT NULL, fecha DATETIME NOT NULL, x DOUBLE PRECISION NOT NULL, y DOUBLE PRECISION NOT NULL, INDEX IDX_698658A78BDC7AE9 (mesa_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE distribucion ADD CONSTRAINT FK_698658A78BDC7AE9 FOREIGN KEY (mesa_id) REFERENCES mesa (id)');
    }
}
