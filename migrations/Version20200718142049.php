<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200718142049 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE anonce_vue (id INT AUTO_INCREMENT NOT NULL, visiteur_id INT NOT NULL, anonce_id INT NOT NULL, date_vue DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cv_anonceur (id INT AUTO_INCREMENT NOT NULL, anonceur_id INT NOT NULL, diplome LONGTEXT NOT NULL, date_enregistrement DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, destinataire VARCHAR(255) NOT NULL, expediteur VARCHAR(255) NOT NULL, destinataire_id INT NOT NULL, expediteur_id INT NOT NULL, message LONGTEXT NOT NULL, media VARCHAR(255) NOT NULL, date_message DATETIME NOT NULL, actif VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recommendation (id INT AUTO_INCREMENT NOT NULL, anonce_id INT NOT NULL, mode_recommendation VARCHAR(255) NOT NULL, date_recommendation DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE anonce_vue');
        $this->addSql('DROP TABLE cv_anonceur');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE recommendation');
    }
}
