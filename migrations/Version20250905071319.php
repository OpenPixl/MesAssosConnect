<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250905071319 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE adhesion (id INT AUTO_INCREMENT NOT NULL, campaign_id INT DEFAULT NULL, cotisation_id INT DEFAULT NULL, price_cotisation NUMERIC(10, 2) DEFAULT NULL, pay_by VARCHAR(100) DEFAULT NULL, pay_at DATE DEFAULT NULL, start_at DATE NOT NULL, finish_at DATE NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_C50CA65AF639F774 (campaign_id), INDEX IDX_C50CA65A3EAA84B1 (cotisation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE adhesion_adherent (adhesion_id INT NOT NULL, adherent_id INT NOT NULL, INDEX IDX_B3C87590F68139D7 (adhesion_id), INDEX IDX_B3C8759025F06C53 (adherent_id), PRIMARY KEY(adhesion_id, adherent_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE adhesion ADD CONSTRAINT FK_C50CA65AF639F774 FOREIGN KEY (campaign_id) REFERENCES campaign_adhesion (id)');
        $this->addSql('ALTER TABLE adhesion ADD CONSTRAINT FK_C50CA65A3EAA84B1 FOREIGN KEY (cotisation_id) REFERENCES cotisation (id)');
        $this->addSql('ALTER TABLE adhesion_adherent ADD CONSTRAINT FK_B3C87590F68139D7 FOREIGN KEY (adhesion_id) REFERENCES adhesion (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE adhesion_adherent ADD CONSTRAINT FK_B3C8759025F06C53 FOREIGN KEY (adherent_id) REFERENCES adherent (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adhesion DROP FOREIGN KEY FK_C50CA65AF639F774');
        $this->addSql('ALTER TABLE adhesion DROP FOREIGN KEY FK_C50CA65A3EAA84B1');
        $this->addSql('ALTER TABLE adhesion_adherent DROP FOREIGN KEY FK_B3C87590F68139D7');
        $this->addSql('ALTER TABLE adhesion_adherent DROP FOREIGN KEY FK_B3C8759025F06C53');
        $this->addSql('DROP TABLE adhesion');
        $this->addSql('DROP TABLE adhesion_adherent');
    }
}
