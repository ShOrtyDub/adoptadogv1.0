<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230829071919 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaire CHANGE fk_id_chien_id fk_id_chien_id INT DEFAULT NULL, CHANGE fk_id_admin_id fk_id_admin_id INT DEFAULT NULL, CHANGE fk_id_utilisateur_id fk_id_utilisateur_id INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaire CHANGE fk_id_chien_id fk_id_chien_id INT NOT NULL, CHANGE fk_id_admin_id fk_id_admin_id INT NOT NULL, CHANGE fk_id_utilisateur_id fk_id_utilisateur_id INT NOT NULL');
    }
}
