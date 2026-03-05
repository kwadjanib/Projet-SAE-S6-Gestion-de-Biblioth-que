<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260305212533 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE auteur_livre DROP FOREIGN KEY `FK_A6DFA5E037D925CB`');
        $this->addSql('ALTER TABLE auteur_livre DROP FOREIGN KEY `FK_A6DFA5E060BB6FE6`');
        $this->addSql('ALTER TABLE categorie_livre DROP FOREIGN KEY `FK_591BA24937D925CB`');
        $this->addSql('ALTER TABLE categorie_livre DROP FOREIGN KEY `FK_591BA249BCF5E72D`');
        $this->addSql('DROP TABLE auteur_livre');
        $this->addSql('DROP TABLE categorie_livre');
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
    }
}
