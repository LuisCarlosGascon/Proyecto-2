<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230207151438 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE presentacion ADD evento_id INT NOT NULL, ADD juego_id INT NOT NULL');
        $this->addSql('ALTER TABLE presentacion ADD CONSTRAINT FK_56A887B587A5F842 FOREIGN KEY (evento_id) REFERENCES evento (id)');
        $this->addSql('ALTER TABLE presentacion ADD CONSTRAINT FK_56A887B513375255 FOREIGN KEY (juego_id) REFERENCES juego (id)');
        $this->addSql('CREATE INDEX IDX_56A887B587A5F842 ON presentacion (evento_id)');
        $this->addSql('CREATE INDEX IDX_56A887B513375255 ON presentacion (juego_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE presentacion DROP FOREIGN KEY FK_56A887B587A5F842');
        $this->addSql('ALTER TABLE presentacion DROP FOREIGN KEY FK_56A887B513375255');
        $this->addSql('DROP INDEX IDX_56A887B587A5F842 ON presentacion');
        $this->addSql('DROP INDEX IDX_56A887B513375255 ON presentacion');
        $this->addSql('ALTER TABLE presentacion DROP evento_id, DROP juego_id');
    }
}
