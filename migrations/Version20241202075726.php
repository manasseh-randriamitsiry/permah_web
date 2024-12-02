<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241202075726 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event ADD media_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE event ADD media_size INT DEFAULT NULL');
        $this->addSql('ALTER TABLE event ADD media_mime_type VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE event ADD updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN event.updated_at IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE event DROP media_name');
        $this->addSql('ALTER TABLE event DROP media_size');
        $this->addSql('ALTER TABLE event DROP media_mime_type');
        $this->addSql('ALTER TABLE event DROP updated_at');
    }
}
