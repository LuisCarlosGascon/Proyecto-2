<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230207151628 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reserva ADD mesa_id INT NOT NULL, ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE reserva ADD CONSTRAINT FK_188D2E3B8BDC7AE9 FOREIGN KEY (mesa_id) REFERENCES mesa (id)');
        $this->addSql('ALTER TABLE reserva ADD CONSTRAINT FK_188D2E3BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_188D2E3B8BDC7AE9 ON reserva (mesa_id)');
        $this->addSql('CREATE INDEX IDX_188D2E3BA76ED395 ON reserva (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reserva DROP FOREIGN KEY FK_188D2E3B8BDC7AE9');
        $this->addSql('ALTER TABLE reserva DROP FOREIGN KEY FK_188D2E3BA76ED395');
        $this->addSql('DROP INDEX IDX_188D2E3B8BDC7AE9 ON reserva');
        $this->addSql('DROP INDEX IDX_188D2E3BA76ED395 ON reserva');
        $this->addSql('ALTER TABLE reserva DROP mesa_id, DROP user_id');
    }
}
