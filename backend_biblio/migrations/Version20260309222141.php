<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260309222141 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE adherent');
        $this->addSql('ALTER TABLE emprunt DROP FOREIGN KEY `FK_364071D725F06C53`');
        $this->addSql('DROP INDEX IDX_364071D725F06C53 ON emprunt');
        $this->addSql('ALTER TABLE emprunt CHANGE adherent_id utilisateur_id INT NOT NULL');
        $this->addSql('ALTER TABLE emprunt ADD CONSTRAINT FK_364071D7FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('CREATE INDEX IDX_364071D7FB88E14F ON emprunt (utilisateur_id)');
        $this->addSql('ALTER TABLE reservations DROP FOREIGN KEY `FK_4DA23925F06C53`');
        $this->addSql('DROP INDEX IDX_4DA23925F06C53 ON reservations');
        $this->addSql('ALTER TABLE reservations CHANGE adherent_id utilisateur_id INT NOT NULL');
        $this->addSql('ALTER TABLE reservations ADD CONSTRAINT FK_4DA239FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('CREATE INDEX IDX_4DA239FB88E14F ON reservations (utilisateur_id)');
        $this->addSql('ALTER TABLE utilisateur ADD date_adhesion DATETIME NOT NULL, ADD date_naiss DATETIME NOT NULL, ADD adresse_postale VARCHAR(255) NOT NULL, ADD num_tel VARCHAR(20) NOT NULL, ADD photo VARCHAR(255) DEFAULT NULL, CHANGE email email VARCHAR(255) NOT NULL, CHANGE nom nom VARCHAR(255) NOT NULL, CHANGE prenom prenom VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE adherent (id INT AUTO_INCREMENT NOT NULL, date_adhesion DATETIME NOT NULL, nom VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, prenom VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, date_naiss DATETIME NOT NULL, email VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, adresse_postale VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, num_tel VARCHAR(20) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, photo VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE emprunt DROP FOREIGN KEY FK_364071D7FB88E14F');
        $this->addSql('DROP INDEX IDX_364071D7FB88E14F ON emprunt');
        $this->addSql('ALTER TABLE emprunt CHANGE utilisateur_id adherent_id INT NOT NULL');
        $this->addSql('ALTER TABLE emprunt ADD CONSTRAINT `FK_364071D725F06C53` FOREIGN KEY (adherent_id) REFERENCES adherent (id)');
        $this->addSql('CREATE INDEX IDX_364071D725F06C53 ON emprunt (adherent_id)');
        $this->addSql('ALTER TABLE reservations DROP FOREIGN KEY FK_4DA239FB88E14F');
        $this->addSql('DROP INDEX IDX_4DA239FB88E14F ON reservations');
        $this->addSql('ALTER TABLE reservations CHANGE utilisateur_id adherent_id INT NOT NULL');
        $this->addSql('ALTER TABLE reservations ADD CONSTRAINT `FK_4DA23925F06C53` FOREIGN KEY (adherent_id) REFERENCES adherent (id)');
        $this->addSql('CREATE INDEX IDX_4DA23925F06C53 ON reservations (adherent_id)');
        $this->addSql('ALTER TABLE utilisateur DROP date_adhesion, DROP date_naiss, DROP adresse_postale, DROP num_tel, DROP photo, CHANGE nom nom VARCHAR(100) NOT NULL, CHANGE prenom prenom VARCHAR(100) NOT NULL, CHANGE email email VARCHAR(180) NOT NULL');
    }
}
