<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200718135531 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE anonce (id INT AUTO_INCREMENT NOT NULL, matiere VARCHAR(255) NOT NULL, type_cours VARCHAR(255) NOT NULL, titre LONGTEXT NOT NULL, parcours LONGTEXT NOT NULL, methodologie LONGTEXT NOT NULL, lieu_cours VARCHAR(255) NOT NULL, tarif_heure INT NOT NULL, photo_profil VARCHAR(255) NOT NULL, actif VARCHAR(255) NOT NULL, anonceur_id INT NOT NULL, date_anonce DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE matiere (id INT AUTO_INCREMENT NOT NULL, cours VARCHAR(255) NOT NULL, branche LONGTEXT NOT NULL, date_ajout DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE anonce');
        $this->addSql('DROP TABLE matiere');
    }
}
