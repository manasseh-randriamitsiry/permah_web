<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241202074818 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event DROP CONSTRAINT fk_3bae0aa7ed766068');
        $this->addSql('DROP INDEX idx_3bae0aa7ed766068');
        $this->addSql('ALTER TABLE event DROP media_file_name');
        $this->addSql('ALTER TABLE event DROP updated_at');
        $this->addSql('ALTER TABLE event ALTER description TYPE TEXT');
        $this->addSql('ALTER TABLE event ALTER description DROP NOT NULL');
        $this->addSql('ALTER TABLE event ALTER location TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE event ALTER price TYPE NUMERIC(10, 2)');
        $this->addSql('ALTER TABLE event ALTER price DROP NOT NULL');
        $this->addSql('ALTER TABLE event RENAME COLUMN username_id TO user_id');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_3BAE0AA7A76ED395 ON event (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE event DROP CONSTRAINT FK_3BAE0AA7A76ED395');
        $this->addSql('DROP INDEX IDX_3BAE0AA7A76ED395');
        $this->addSql('ALTER TABLE event ADD media_file_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE event ADD updated_at VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE event ALTER description TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE event ALTER description SET NOT NULL');
        $this->addSql('ALTER TABLE event ALTER location TYPE VARCHAR(200)');
        $this->addSql('ALTER TABLE event ALTER price TYPE DOUBLE PRECISION');
        $this->addSql('ALTER TABLE event ALTER price SET NOT NULL');
        $this->addSql('ALTER TABLE event RENAME COLUMN user_id TO username_id');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT fk_3bae0aa7ed766068 FOREIGN KEY (username_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_3bae0aa7ed766068 ON event (username_id)');
    }
}
