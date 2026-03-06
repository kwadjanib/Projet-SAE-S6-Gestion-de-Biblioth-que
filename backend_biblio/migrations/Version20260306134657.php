<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260306134657 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE livre_auteur (livre_id INT NOT NULL, auteur_id INT NOT NULL, INDEX IDX_A11876B537D925CB (livre_id), INDEX IDX_A11876B560BB6FE6 (auteur_id), PRIMARY KEY (livre_id, auteur_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE livre_categorie (livre_id INT NOT NULL, categorie_id INT NOT NULL, INDEX IDX_E61B069E37D925CB (livre_id), INDEX IDX_E61B069EBCF5E72D (categorie_id), PRIMARY KEY (livre_id, categorie_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE livre_auteur ADD CONSTRAINT FK_A11876B537D925CB FOREIGN KEY (livre_id) REFERENCES livre (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE livre_auteur ADD CONSTRAINT FK_A11876B560BB6FE6 FOREIGN KEY (auteur_id) REFERENCES auteur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE livre_categorie ADD CONSTRAINT FK_E61B069E37D925CB FOREIGN KEY (livre_id) REFERENCES livre (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE livre_categorie ADD CONSTRAINT FK_E61B069EBCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE auteur_livre DROP FOREIGN KEY `FK_A6DFA5E037D925CB`');
        $this->addSql('ALTER TABLE auteur_livre DROP FOREIGN KEY `FK_A6DFA5E060BB6FE6`');
        $this->addSql('ALTER TABLE categorie_livre DROP FOREIGN KEY `FK_591BA24937D925CB`');
        $this->addSql('ALTER TABLE categorie_livre DROP FOREIGN KEY `FK_591BA249BCF5E72D`');
        $this->addSql('DROP TABLE auteur_livre');
        $this->addSql('DROP TABLE categorie_livre');
        $this->addSql('ALTER TABLE adherent CHANGE num_tel num_tel VARCHAR(20) NOT NULL');
        $this->addSql('ALTER TABLE emprunt ADD livre_id INT NOT NULL');
        $this->addSql('ALTER TABLE emprunt ADD CONSTRAINT FK_364071D737D925CB FOREIGN KEY (livre_id) REFERENCES livre (id)');
        $this->addSql('CREATE INDEX IDX_364071D737D925CB ON emprunt (livre_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE auteur_livre (auteur_id INT NOT NULL, livre_id INT NOT NULL, INDEX IDX_A6DFA5E037D925CB (livre_id), INDEX IDX_A6DFA5E060BB6FE6 (auteur_id), PRIMARY KEY (auteur_id, livre_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE categorie_livre (categorie_id INT NOT NULL, livre_id INT NOT NULL, INDEX IDX_591BA249BCF5E72D (categorie_id), INDEX IDX_591BA24937D925CB (livre_id), PRIMARY KEY (categorie_id, livre_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE auteur_livre ADD CONSTRAINT `FK_A6DFA5E037D925CB` FOREIGN KEY (livre_id) REFERENCES livre (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE auteur_livre ADD CONSTRAINT `FK_A6DFA5E060BB6FE6` FOREIGN KEY (auteur_id) REFERENCES auteur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE categorie_livre ADD CONSTRAINT `FK_591BA24937D925CB` FOREIGN KEY (livre_id) REFERENCES livre (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE categorie_livre ADD CONSTRAINT `FK_591BA249BCF5E72D` FOREIGN KEY (categorie_id) REFERENCES categorie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE livre_auteur DROP FOREIGN KEY FK_A11876B537D925CB');
        $this->addSql('ALTER TABLE livre_auteur DROP FOREIGN KEY FK_A11876B560BB6FE6');
        $this->addSql('ALTER TABLE livre_categorie DROP FOREIGN KEY FK_E61B069E37D925CB');
        $this->addSql('ALTER TABLE livre_categorie DROP FOREIGN KEY FK_E61B069EBCF5E72D');
        $this->addSql('DROP TABLE livre_auteur');
        $this->addSql('DROP TABLE livre_categorie');
        $this->addSql('ALTER TABLE adherent CHANGE num_tel num_tel INT NOT NULL');
        $this->addSql('ALTER TABLE emprunt DROP FOREIGN KEY FK_364071D737D925CB');
        $this->addSql('DROP INDEX IDX_364071D737D925CB ON emprunt');
        $this->addSql('ALTER TABLE emprunt DROP livre_id');
    }
}
