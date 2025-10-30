<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250921174853 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category_activities (id INT AUTO_INCREMENT NOT NULL, association_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_93EFFF9BEFB9C8A5 (association_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE price_activities (id INT AUTO_INCREMENT NOT NULL, activity_id INT DEFAULT NULL, campaign_id INT DEFAULT NULL, INDEX IDX_179A0E181C06096 (activity_id), INDEX IDX_179A0E1F639F774 (campaign_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE category_activities ADD CONSTRAINT FK_93EFFF9BEFB9C8A5 FOREIGN KEY (association_id) REFERENCES association (id)');
        $this->addSql('ALTER TABLE price_activities ADD CONSTRAINT FK_179A0E181C06096 FOREIGN KEY (activity_id) REFERENCES activity (id)');
        $this->addSql('ALTER TABLE price_activities ADD CONSTRAINT FK_179A0E1F639F774 FOREIGN KEY (campaign_id) REFERENCES campaign_adhesion (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category_activities DROP FOREIGN KEY FK_93EFFF9BEFB9C8A5');
        $this->addSql('ALTER TABLE price_activities DROP FOREIGN KEY FK_179A0E181C06096');
        $this->addSql('ALTER TABLE price_activities DROP FOREIGN KEY FK_179A0E1F639F774');
        $this->addSql('DROP TABLE category_activities');
        $this->addSql('DROP TABLE price_activities');
    }
}
