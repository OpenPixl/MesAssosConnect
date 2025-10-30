<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250924061847 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE activity_member (activity_id INT NOT NULL, member_id INT NOT NULL, INDEX IDX_22921A7881C06096 (activity_id), INDEX IDX_22921A787597D3FE (member_id), PRIMARY KEY(activity_id, member_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE activity_member ADD CONSTRAINT FK_22921A7881C06096 FOREIGN KEY (activity_id) REFERENCES activity (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE activity_member ADD CONSTRAINT FK_22921A787597D3FE FOREIGN KEY (member_id) REFERENCES member (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE activity DROP FOREIGN KEY FK_AC74095ACA8BA400');
        $this->addSql('DROP INDEX IDX_AC74095ACA8BA400 ON activity');
        $this->addSql('ALTER TABLE activity CHANGE assocotion_id asso_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE activity ADD CONSTRAINT FK_AC74095A792C8628 FOREIGN KEY (asso_id) REFERENCES association (id)');
        $this->addSql('CREATE INDEX IDX_AC74095A792C8628 ON activity (asso_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activity_member DROP FOREIGN KEY FK_22921A7881C06096');
        $this->addSql('ALTER TABLE activity_member DROP FOREIGN KEY FK_22921A787597D3FE');
        $this->addSql('DROP TABLE activity_member');
        $this->addSql('ALTER TABLE activity DROP FOREIGN KEY FK_AC74095A792C8628');
        $this->addSql('DROP INDEX IDX_AC74095A792C8628 ON activity');
        $this->addSql('ALTER TABLE activity CHANGE asso_id assocotion_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE activity ADD CONSTRAINT FK_AC74095ACA8BA400 FOREIGN KEY (assocotion_id) REFERENCES association (id)');
        $this->addSql('CREATE INDEX IDX_AC74095ACA8BA400 ON activity (assocotion_id)');
    }
}
