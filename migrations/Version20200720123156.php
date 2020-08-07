<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200720123156 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE anonce (id INT AUTO_INCREMENT NOT NULL, matiere VARCHAR(255) NOT NULL, type_cours VARCHAR(255) NOT NULL, titre LONGTEXT NOT NULL, parcours LONGTEXT NOT NULL, methodologie LONGTEXT NOT NULL, lieu_cours VARCHAR(255) NOT NULL, tarif_heure INT NOT NULL, photo_profil VARCHAR(255) NOT NULL, actif VARCHAR(255) NOT NULL, anonceur_id INT NOT NULL, date_anonce DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE anonce_vue (id INT AUTO_INCREMENT NOT NULL, visiteur_id INT NOT NULL, anonce_id INT NOT NULL, date_vue DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE avis (id INT AUTO_INCREMENT NOT NULL, anonce_id INT NOT NULL, user_id INT NOT NULL, avis LONGTEXT NOT NULL, status INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cv_anonceur (id INT AUTO_INCREMENT NOT NULL, anonceur_id INT NOT NULL, diplome LONGTEXT NOT NULL, date_enregistrement DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE matiere (id INT AUTO_INCREMENT NOT NULL, cours VARCHAR(255) NOT NULL, branche LONGTEXT NOT NULL, date_ajout DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, destinataire VARCHAR(255) NOT NULL, expediteur VARCHAR(255) NOT NULL, destinataire_id INT NOT NULL, expediteur_id INT NOT NULL, message LONGTEXT NOT NULL, media VARCHAR(255) NOT NULL, date_message DATETIME NOT NULL, actif VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notification (id INT AUTO_INCREMENT NOT NULL, anonceur_id INT NOT NULL, reserveur_id INT NOT NULL, anonce_id INT NOT NULL, lien VARCHAR(255) NOT NULL, status INT NOT NULL, date_notification DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recommendation (id INT AUTO_INCREMENT NOT NULL, anonce_id INT NOT NULL, mode_recommendation VARCHAR(255) NOT NULL, date_recommendation DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reserve_cours (id INT AUTO_INCREMENT NOT NULL, message_contact LONGTEXT NOT NULL, format_cours VARCHAR(255) NOT NULL, coordonnee_contact LONGTEXT NOT NULL, mode_payement VARCHAR(255) NOT NULL, date_reservation DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, phone_portable VARCHAR(255) NOT NULL, phone_fixe VARCHAR(255) NOT NULL, genre VARCHAR(255) NOT NULL, ville VARCHAR(255) NOT NULL, pays VARCHAR(255) NOT NULL, actif VARCHAR(255) NOT NULL, date_inscrit DATETIME NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE anonce');
        $this->addSql('DROP TABLE anonce_vue');
        $this->addSql('DROP TABLE avis');
        $this->addSql('DROP TABLE cv_anonceur');
        $this->addSql('DROP TABLE matiere');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE notification');
        $this->addSql('DROP TABLE recommendation');
        $this->addSql('DROP TABLE reserve_cours');
        $this->addSql('DROP TABLE user');
    }
}
