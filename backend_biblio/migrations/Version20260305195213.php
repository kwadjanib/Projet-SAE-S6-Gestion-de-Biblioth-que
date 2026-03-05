<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260305195213 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE adherent (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, date_adhesion DATETIME NOT NULL, date_naiss DATETIME NOT NULL, adresse_postale VARCHAR(255) NOT NULL, num_tel VARCHAR(255) NOT NULL, photo VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_90D3F060E7927C74 (email), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE auteur (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, date_naissance DATETIME NOT NULL, date_deces DATETIME DEFAULT NULL, nationalite VARCHAR(255) DEFAULT NULL, photo VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE auteur_livre (auteur_id INT NOT NULL, livre_id INT NOT NULL, INDEX IDX_A6DFA5E060BB6FE6 (auteur_id), INDEX IDX_A6DFA5E037D925CB (livre_id), PRIMARY KEY (auteur_id, livre_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE categorie_livre (categorie_id INT NOT NULL, livre_id INT NOT NULL, INDEX IDX_591BA249BCF5E72D (categorie_id), INDEX IDX_591BA24937D925CB (livre_id), PRIMARY KEY (categorie_id, livre_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE emprunt (id INT AUTO_INCREMENT NOT NULL, date_emprunt DATETIME NOT NULL, date_retour DATETIME DEFAULT NULL, adherent_id INT NOT NULL, livre_id INT NOT NULL, INDEX IDX_364071D725F06C53 (adherent_id), INDEX IDX_364071D737D925CB (livre_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE livre (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, date_sortie DATETIME NOT NULL, langue VARCHAR(255) NOT NULL, photo_couverture VARCHAR(255) DEFAULT NULL, emprunt_id INT DEFAULT NULL, INDEX IDX_AC634F99AE7FEF94 (emprunt_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE reservations (id INT AUTO_INCREMENT NOT NULL, date_resa DATETIME NOT NULL, adherent_id INT NOT NULL, livre_id INT NOT NULL, INDEX IDX_4DA23925F06C53 (adherent_id), INDEX IDX_4DA23937D925CB (livre_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE auteur_livre ADD CONSTRAINT FK_A6DFA5E060BB6FE6 FOREIGN KEY (auteur_id) REFERENCES auteur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE auteur_livre ADD CONSTRAINT FK_A6DFA5E037D925CB FOREIGN KEY (livre_id) REFERENCES livre (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE categorie_livre ADD CONSTRAINT FK_591BA249BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE categorie_livre ADD CONSTRAINT FK_591BA24937D925CB FOREIGN KEY (livre_id) REFERENCES livre (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE emprunt ADD CONSTRAINT FK_364071D725F06C53 FOREIGN KEY (adherent_id) REFERENCES adherent (id)');
        $this->addSql('ALTER TABLE emprunt ADD CONSTRAINT FK_364071D737D925CB FOREIGN KEY (livre_id) REFERENCES livre (id)');
        $this->addSql('ALTER TABLE livre ADD CONSTRAINT FK_AC634F99AE7FEF94 FOREIGN KEY (emprunt_id) REFERENCES emprunt (id)');
        $this->addSql('ALTER TABLE reservations ADD CONSTRAINT FK_4DA23925F06C53 FOREIGN KEY (adherent_id) REFERENCES adherent (id)');
        $this->addSql('ALTER TABLE reservations ADD CONSTRAINT FK_4DA23937D925CB FOREIGN KEY (livre_id) REFERENCES livre (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE auteur_livre DROP FOREIGN KEY FK_A6DFA5E060BB6FE6');
        $this->addSql('ALTER TABLE auteur_livre DROP FOREIGN KEY FK_A6DFA5E037D925CB');
        $this->addSql('ALTER TABLE categorie_livre DROP FOREIGN KEY FK_591BA249BCF5E72D');
        $this->addSql('ALTER TABLE categorie_livre DROP FOREIGN KEY FK_591BA24937D925CB');
        $this->addSql('ALTER TABLE emprunt DROP FOREIGN KEY FK_364071D725F06C53');
        $this->addSql('ALTER TABLE emprunt DROP FOREIGN KEY FK_364071D737D925CB');
        $this->addSql('ALTER TABLE livre DROP FOREIGN KEY FK_AC634F99AE7FEF94');
        $this->addSql('ALTER TABLE reservations DROP FOREIGN KEY FK_4DA23925F06C53');
        $this->addSql('ALTER TABLE reservations DROP FOREIGN KEY FK_4DA23937D925CB');
        $this->addSql('DROP TABLE adherent');
        $this->addSql('DROP TABLE auteur');
        $this->addSql('DROP TABLE auteur_livre');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE categorie_livre');
        $this->addSql('DROP TABLE emprunt');
        $this->addSql('DROP TABLE livre');
        $this->addSql('DROP TABLE reservations');
    }
}
