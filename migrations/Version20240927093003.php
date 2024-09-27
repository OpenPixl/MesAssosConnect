<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240927093003 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE adhesion (id INT AUTO_INCREMENT NOT NULL, asso_id INT DEFAULT NULL, type_adhesion_id INT DEFAULT NULL, cotisation NUMERIC(10, 2) DEFAULT NULL, is_paid TINYINT(1) NOT NULL, is_free TINYINT(1) NOT NULL, paid_at DATE DEFAULT NULL, paid_by VARCHAR(50) DEFAULT NULL, ref_paid VARCHAR(50) DEFAULT NULL, create_at DATETIME NOT NULL, update_at DATETIME DEFAULT NULL, INDEX IDX_C50CA65A792C8628 (asso_id), INDEX IDX_C50CA65ABD98EE46 (type_adhesion_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE adhesion_member (adhesion_id INT NOT NULL, member_id INT NOT NULL, INDEX IDX_4554AAC4F68139D7 (adhesion_id), INDEX IDX_4554AAC47597D3FE (member_id), PRIMARY KEY(adhesion_id, member_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE association (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, slug VARCHAR(100) DEFAULT NULL, object LONGTEXT DEFAULT NULL, is_rna TINYINT(1) NOT NULL, num_rna VARCHAR(30) DEFAULT NULL, address VARCHAR(150) DEFAULT NULL, bis_address VARCHAR(150) DEFAULT NULL, zipcode VARCHAR(150) DEFAULT NULL, city VARCHAR(100) DEFAULT NULL, contact_phone VARCHAR(14) DEFAULT NULL, contact_email VARCHAR(50) NOT NULL, site VARCHAR(100) DEFAULT NULL, link_fb VARCHAR(100) DEFAULT NULL, link_inst VARCHAR(100) DEFAULT NULL, link_goo VARCHAR(100) DEFAULT NULL, logo_name VARCHAR(255) DEFAULT NULL, logo_size INT DEFAULT NULL, logo_ext VARCHAR(255) DEFAULT NULL, create_at DATETIME NOT NULL, update_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE compo_association (id INT AUTO_INCREMENT NOT NULL, ref_adherent_id INT DEFAULT NULL, role_id INT DEFAULT NULL, INDEX IDX_FB0AC916DFF994DF (ref_adherent_id), INDEX IDX_FB0AC916D60322AC (role_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE member (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, role_member VARCHAR(50) NOT NULL, civility VARCHAR(5) DEFAULT NULL, first_name VARCHAR(100) DEFAULT NULL, last_name VARCHAR(100) DEFAULT NULL, address VARCHAR(150) DEFAULT NULL, bis_address VARCHAR(150) DEFAULT NULL, zipcode VARCHAR(150) DEFAULT NULL, city VARCHAR(100) DEFAULT NULL, mobile_phone VARCHAR(14) NOT NULL, home_phone VARCHAR(14) DEFAULT NULL, work_phone VARCHAR(14) DEFAULT NULL, avatar_name VARCHAR(255) DEFAULT NULL, avatar_size INT DEFAULT NULL, avatar_ext VARCHAR(255) DEFAULT NULL, create_at DATETIME NOT NULL, update_at DATETIME DEFAULT NULL, is_verified TINYINT(1) NOT NULL, birthday DATE DEFAULT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role_association (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, create_at DATETIME NOT NULL, update_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE saison (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, create_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', update_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_adhesion (id INT AUTO_INCREMENT NOT NULL, asso_id INT DEFAULT NULL, name VARCHAR(100) NOT NULL, price_cotisation NUMERIC(10, 2) DEFAULT NULL, start_at DATE NOT NULL, end_at DATE NOT NULL, notes LONGTEXT DEFAULT NULL, create_at DATETIME NOT NULL, update_at DATETIME DEFAULT NULL, INDEX IDX_8F381A6792C8628 (asso_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE adhesion ADD CONSTRAINT FK_C50CA65A792C8628 FOREIGN KEY (asso_id) REFERENCES association (id)');
        $this->addSql('ALTER TABLE adhesion ADD CONSTRAINT FK_C50CA65ABD98EE46 FOREIGN KEY (type_adhesion_id) REFERENCES type_adhesion (id)');
        $this->addSql('ALTER TABLE adhesion_member ADD CONSTRAINT FK_4554AAC4F68139D7 FOREIGN KEY (adhesion_id) REFERENCES adhesion (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE adhesion_member ADD CONSTRAINT FK_4554AAC47597D3FE FOREIGN KEY (member_id) REFERENCES member (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE compo_association ADD CONSTRAINT FK_FB0AC916DFF994DF FOREIGN KEY (ref_adherent_id) REFERENCES member (id)');
        $this->addSql('ALTER TABLE compo_association ADD CONSTRAINT FK_FB0AC916D60322AC FOREIGN KEY (role_id) REFERENCES role_association (id)');
        $this->addSql('ALTER TABLE type_adhesion ADD CONSTRAINT FK_8F381A6792C8628 FOREIGN KEY (asso_id) REFERENCES association (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adhesion DROP FOREIGN KEY FK_C50CA65A792C8628');
        $this->addSql('ALTER TABLE adhesion DROP FOREIGN KEY FK_C50CA65ABD98EE46');
        $this->addSql('ALTER TABLE adhesion_member DROP FOREIGN KEY FK_4554AAC4F68139D7');
        $this->addSql('ALTER TABLE adhesion_member DROP FOREIGN KEY FK_4554AAC47597D3FE');
        $this->addSql('ALTER TABLE compo_association DROP FOREIGN KEY FK_FB0AC916DFF994DF');
        $this->addSql('ALTER TABLE compo_association DROP FOREIGN KEY FK_FB0AC916D60322AC');
        $this->addSql('ALTER TABLE type_adhesion DROP FOREIGN KEY FK_8F381A6792C8628');
        $this->addSql('DROP TABLE adhesion');
        $this->addSql('DROP TABLE adhesion_member');
        $this->addSql('DROP TABLE association');
        $this->addSql('DROP TABLE compo_association');
        $this->addSql('DROP TABLE member');
        $this->addSql('DROP TABLE role_association');
        $this->addSql('DROP TABLE saison');
        $this->addSql('DROP TABLE type_adhesion');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
