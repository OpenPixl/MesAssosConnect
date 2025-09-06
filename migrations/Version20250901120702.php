<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250901120702 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE campaign_adhesion DROP FOREIGN KEY FK_F77C702525F06C53');
        $this->addSql('DROP INDEX IDX_F77C702525F06C53 ON campaign_adhesion');
        $this->addSql('ALTER TABLE campaign_adhesion ADD start_at DATE NOT NULL, ADD finish_at DATE NOT NULL, CHANGE adherent_id association_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE campaign_adhesion ADD CONSTRAINT FK_F77C7025EFB9C8A5 FOREIGN KEY (association_id) REFERENCES association (id)');
        $this->addSql('CREATE INDEX IDX_F77C7025EFB9C8A5 ON campaign_adhesion (association_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE campaign_adhesion DROP FOREIGN KEY FK_F77C7025EFB9C8A5');
        $this->addSql('DROP INDEX IDX_F77C7025EFB9C8A5 ON campaign_adhesion');
        $this->addSql('ALTER TABLE campaign_adhesion DROP start_at, DROP finish_at, CHANGE association_id adherent_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE campaign_adhesion ADD CONSTRAINT FK_F77C702525F06C53 FOREIGN KEY (adherent_id) REFERENCES adherent (id)');
        $this->addSql('CREATE INDEX IDX_F77C702525F06C53 ON campaign_adhesion (adherent_id)');
    }
}
