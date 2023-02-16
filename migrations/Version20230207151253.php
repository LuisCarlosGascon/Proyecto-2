<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230207151253 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE invitacion ADD user_id INT NOT NULL, ADD evento_id INT NOT NULL');
        $this->addSql('ALTER TABLE invitacion ADD CONSTRAINT FK_3CD30E84A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE invitacion ADD CONSTRAINT FK_3CD30E8487A5F842 FOREIGN KEY (evento_id) REFERENCES evento (id)');
        $this->addSql('CREATE INDEX IDX_3CD30E84A76ED395 ON invitacion (user_id)');
        $this->addSql('CREATE INDEX IDX_3CD30E8487A5F842 ON invitacion (evento_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE invitacion DROP FOREIGN KEY FK_3CD30E84A76ED395');
        $this->addSql('ALTER TABLE invitacion DROP FOREIGN KEY FK_3CD30E8487A5F842');
        $this->addSql('DROP INDEX IDX_3CD30E84A76ED395 ON invitacion');
        $this->addSql('DROP INDEX IDX_3CD30E8487A5F842 ON invitacion');
        $this->addSql('ALTER TABLE invitacion DROP user_id, DROP evento_id');
    }
}
