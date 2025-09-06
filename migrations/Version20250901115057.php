<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250901115057 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE adherent (id INT AUTO_INCREMENT NOT NULL, association_id INT DEFAULT NULL, member_id INT DEFAULT NULL, state VARCHAR(20) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_90D3F060EFB9C8A5 (association_id), INDEX IDX_90D3F0607597D3FE (member_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE association (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, slug VARCHAR(100) DEFAULT NULL, object LONGTEXT DEFAULT NULL, is_rna TINYINT(1) NOT NULL, num_rna VARCHAR(30) DEFAULT NULL, address VARCHAR(150) DEFAULT NULL, bis_address VARCHAR(150) DEFAULT NULL, zipcode VARCHAR(150) DEFAULT NULL, city VARCHAR(100) DEFAULT NULL, contact_phone VARCHAR(14) DEFAULT NULL, contact_email VARCHAR(50) NOT NULL, site VARCHAR(100) DEFAULT NULL, link_fb VARCHAR(100) DEFAULT NULL, link_inst VARCHAR(100) DEFAULT NULL, link_goo VARCHAR(100) DEFAULT NULL, logo_name VARCHAR(255) DEFAULT NULL, logo_size INT DEFAULT NULL, logo_ext VARCHAR(255) DEFAULT NULL, season_start VARCHAR(5) DEFAULT NULL, season_end VARCHAR(5) DEFAULT NULL, create_at DATETIME NOT NULL, update_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE campaign_adhesion (id INT AUTO_INCREMENT NOT NULL, adherent_id INT DEFAULT NULL, INDEX IDX_F77C702525F06C53 (adherent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cotisation (id INT AUTO_INCREMENT NOT NULL, association_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, cotisation INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_AE64D2EDEFB9C8A5 (association_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE member (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, civility VARCHAR(5) DEFAULT NULL, first_name VARCHAR(100) DEFAULT NULL, last_name VARCHAR(100) DEFAULT NULL, address VARCHAR(150) DEFAULT NULL, bis_address VARCHAR(150) DEFAULT NULL, zipcode VARCHAR(150) DEFAULT NULL, city VARCHAR(100) DEFAULT NULL, mobile_phone VARCHAR(14) DEFAULT NULL, home_phone VARCHAR(14) DEFAULT NULL, work_phone VARCHAR(14) DEFAULT NULL, avatar_name VARCHAR(255) DEFAULT NULL, avatar_size INT DEFAULT NULL, avatar_ext VARCHAR(255) DEFAULT NULL, create_at DATETIME NOT NULL, update_at DATETIME DEFAULT NULL, is_verified TINYINT(1) NOT NULL, birthday DATE DEFAULT NULL, type_member INT NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE adherent ADD CONSTRAINT FK_90D3F060EFB9C8A5 FOREIGN KEY (association_id) REFERENCES association (id)');
        $this->addSql('ALTER TABLE adherent ADD CONSTRAINT FK_90D3F0607597D3FE FOREIGN KEY (member_id) REFERENCES member (id)');
        $this->addSql('ALTER TABLE campaign_adhesion ADD CONSTRAINT FK_F77C702525F06C53 FOREIGN KEY (adherent_id) REFERENCES adherent (id)');
        $this->addSql('ALTER TABLE cotisation ADD CONSTRAINT FK_AE64D2EDEFB9C8A5 FOREIGN KEY (association_id) REFERENCES association (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adherent DROP FOREIGN KEY FK_90D3F060EFB9C8A5');
        $this->addSql('ALTER TABLE adherent DROP FOREIGN KEY FK_90D3F0607597D3FE');
        $this->addSql('ALTER TABLE campaign_adhesion DROP FOREIGN KEY FK_F77C702525F06C53');
        $this->addSql('ALTER TABLE cotisation DROP FOREIGN KEY FK_AE64D2EDEFB9C8A5');
        $this->addSql('DROP TABLE adherent');
        $this->addSql('DROP TABLE association');
        $this->addSql('DROP TABLE campaign_adhesion');
        $this->addSql('DROP TABLE cotisation');
        $this->addSql('DROP TABLE member');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
