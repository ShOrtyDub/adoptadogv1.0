<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230828123924 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `admin` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(50) NOT NULL, prenom VARCHAR(50) NOT NULL, UNIQUE INDEX UNIQ_880E0D76E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE chien (id INT AUTO_INCREMENT NOT NULL, fk_id_admin_id INT NOT NULL, nom VARCHAR(50) NOT NULL, age INT NOT NULL, race VARCHAR(50) NOT NULL, couleur VARCHAR(50) NOT NULL, photo VARCHAR(255) NOT NULL, taille INT NOT NULL, poids INT NOT NULL, caractere VARCHAR(50) NOT NULL, description LONGTEXT DEFAULT NULL, INDEX IDX_13A4067E5D06C2B1 (fk_id_admin_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentaire (id INT AUTO_INCREMENT NOT NULL, fk_id_chien_id INT NOT NULL, fk_id_admin_id INT NOT NULL, fk_id_utilisateur_id INT NOT NULL, texte LONGTEXT NOT NULL, date_creation DATETIME NOT NULL, is_valide TINYINT(1) NOT NULL, INDEX IDX_67F068BC86E200AF (fk_id_chien_id), INDEX IDX_67F068BC5D06C2B1 (fk_id_admin_id), INDEX IDX_67F068BC8D9D2B1 (fk_id_utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE correspondance (id INT AUTO_INCREMENT NOT NULL, fk_id_chien_id INT NOT NULL, fk_id_utilisateur_id INT NOT NULL, INDEX IDX_A562D1E786E200AF (fk_id_chien_id), INDEX IDX_A562D1E78D9D2B1 (fk_id_utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, fk_id_admin_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(50) NOT NULL, prenom VARCHAR(50) NOT NULL, age INT NOT NULL, photo VARCHAR(255) DEFAULT NULL, telephone VARCHAR(10) DEFAULT NULL, is_valide TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_1D1C63B3E7927C74 (email), INDEX IDX_1D1C63B35D06C2B1 (fk_id_admin_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE chien ADD CONSTRAINT FK_13A4067E5D06C2B1 FOREIGN KEY (fk_id_admin_id) REFERENCES `admin` (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC86E200AF FOREIGN KEY (fk_id_chien_id) REFERENCES chien (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC5D06C2B1 FOREIGN KEY (fk_id_admin_id) REFERENCES `admin` (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC8D9D2B1 FOREIGN KEY (fk_id_utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE correspondance ADD CONSTRAINT FK_A562D1E786E200AF FOREIGN KEY (fk_id_chien_id) REFERENCES chien (id)');
        $this->addSql('ALTER TABLE correspondance ADD CONSTRAINT FK_A562D1E78D9D2B1 FOREIGN KEY (fk_id_utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT FK_1D1C63B35D06C2B1 FOREIGN KEY (fk_id_admin_id) REFERENCES `admin` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chien DROP FOREIGN KEY FK_13A4067E5D06C2B1');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC86E200AF');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC5D06C2B1');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC8D9D2B1');
        $this->addSql('ALTER TABLE correspondance DROP FOREIGN KEY FK_A562D1E786E200AF');
        $this->addSql('ALTER TABLE correspondance DROP FOREIGN KEY FK_A562D1E78D9D2B1');
        $this->addSql('ALTER TABLE utilisateur DROP FOREIGN KEY FK_1D1C63B35D06C2B1');
        $this->addSql('DROP TABLE `admin`');
        $this->addSql('DROP TABLE chien');
        $this->addSql('DROP TABLE commentaire');
        $this->addSql('DROP TABLE correspondance');
        $this->addSql('DROP TABLE utilisateur');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
