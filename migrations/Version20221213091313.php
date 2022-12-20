<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221213091313 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE annonce (id INT AUTO_INCREMENT NOT NULL, id_user_id INT NOT NULL, titre VARCHAR(255) NOT NULL, description VARCHAR(1000) NOT NULL, adresse VARCHAR(255) NOT NULL, ville VARCHAR(255) NOT NULL, code_postal VARCHAR(255) NOT NULL, prix DOUBLE PRECISION NOT NULL, datecreation DATETIME NOT NULL, status VARCHAR(255) NOT NULL, INDEX IDX_F65593E579F37AE5 (id_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE avis_annonce (id INT AUTO_INCREMENT NOT NULL, id_user_id INT NOT NULL, id_annonce_id INT NOT NULL, message VARCHAR(2000) NOT NULL, INDEX IDX_DD5603E379F37AE5 (id_user_id), INDEX IDX_DD5603E32D8F2BF8 (id_annonce_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, lebelle_option_annonce_id INT NOT NULL, libelle VARCHAR(1000) NOT NULL, status VARCHAR(255) NOT NULL, INDEX IDX_64C19C1D57608F2 (lebelle_option_annonce_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE document_proprietaire (id INT AUTO_INCREMENT NOT NULL, id_user_id INT NOT NULL, lien VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, INDEX IDX_C406B8A779F37AE5 (id_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image_annonce (id INT AUTO_INCREMENT NOT NULL, id_annonce_id INT NOT NULL, lien VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, INDEX IDX_6345C4392D8F2BF8 (id_annonce_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lebelle_option_annonce (id INT AUTO_INCREMENT NOT NULL, value VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE option_annonce (id INT AUTO_INCREMENT NOT NULL, id_annonce_id INT NOT NULL, id_libelle_id INT NOT NULL, valeur VARCHAR(2000) NOT NULL, INDEX IDX_EEA184902D8F2BF8 (id_annonce_id), INDEX IDX_EEA184908057B15A (id_libelle_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, id_annonce_id INT DEFAULT NULL, id_user_id INT DEFAULT NULL, date_debut DATETIME NOT NULL, date_fin DATETIME NOT NULL, nb_nuit INT NOT NULL, total VARCHAR(255) NOT NULL, statue VARCHAR(255) NOT NULL, statue_payment VARCHAR(255) NOT NULL, INDEX IDX_42C849552D8F2BF8 (id_annonce_id), INDEX IDX_42C8495579F37AE5 (id_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, telephone VARCHAR(25) NOT NULL, recup_mdp VARCHAR(255) DEFAULT NULL, adresse VARCHAR(255) DEFAULT NULL, commentaire VARCHAR(255) DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, firstname VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE annonce ADD CONSTRAINT FK_F65593E579F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE avis_annonce ADD CONSTRAINT FK_DD5603E379F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE avis_annonce ADD CONSTRAINT FK_DD5603E32D8F2BF8 FOREIGN KEY (id_annonce_id) REFERENCES annonce (id)');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C1D57608F2 FOREIGN KEY (lebelle_option_annonce_id) REFERENCES lebelle_option_annonce (id)');
        $this->addSql('ALTER TABLE document_proprietaire ADD CONSTRAINT FK_C406B8A779F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE image_annonce ADD CONSTRAINT FK_6345C4392D8F2BF8 FOREIGN KEY (id_annonce_id) REFERENCES annonce (id)');
        $this->addSql('ALTER TABLE option_annonce ADD CONSTRAINT FK_EEA184902D8F2BF8 FOREIGN KEY (id_annonce_id) REFERENCES annonce (id)');
        $this->addSql('ALTER TABLE option_annonce ADD CONSTRAINT FK_EEA184908057B15A FOREIGN KEY (id_libelle_id) REFERENCES lebelle_option_annonce (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C849552D8F2BF8 FOREIGN KEY (id_annonce_id) REFERENCES annonce (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C8495579F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE annonce DROP FOREIGN KEY FK_F65593E579F37AE5');
        $this->addSql('ALTER TABLE avis_annonce DROP FOREIGN KEY FK_DD5603E379F37AE5');
        $this->addSql('ALTER TABLE avis_annonce DROP FOREIGN KEY FK_DD5603E32D8F2BF8');
        $this->addSql('ALTER TABLE category DROP FOREIGN KEY FK_64C19C1D57608F2');
        $this->addSql('ALTER TABLE document_proprietaire DROP FOREIGN KEY FK_C406B8A779F37AE5');
        $this->addSql('ALTER TABLE image_annonce DROP FOREIGN KEY FK_6345C4392D8F2BF8');
        $this->addSql('ALTER TABLE option_annonce DROP FOREIGN KEY FK_EEA184902D8F2BF8');
        $this->addSql('ALTER TABLE option_annonce DROP FOREIGN KEY FK_EEA184908057B15A');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C849552D8F2BF8');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C8495579F37AE5');
        $this->addSql('DROP TABLE annonce');
        $this->addSql('DROP TABLE avis_annonce');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE document_proprietaire');
        $this->addSql('DROP TABLE image_annonce');
        $this->addSql('DROP TABLE lebelle_option_annonce');
        $this->addSql('DROP TABLE option_annonce');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE user');
    }
}
