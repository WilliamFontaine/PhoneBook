<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231114135828 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE contacts (id UUID NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, profile_picture VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_33401573444F97DD ON contacts (phone)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_33401573E7927C74 ON contacts (email)');
        $this->addSql('COMMENT ON COLUMN contacts.id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE contacts_groups (contacts_id UUID NOT NULL, groups_id UUID NOT NULL, PRIMARY KEY(contacts_id, groups_id))');
        $this->addSql('CREATE INDEX IDX_3DA8886B719FB48E ON contacts_groups (contacts_id)');
        $this->addSql('CREATE INDEX IDX_3DA8886BF373DCF ON contacts_groups (groups_id)');
        $this->addSql('COMMENT ON COLUMN contacts_groups.contacts_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN contacts_groups.groups_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE groups (id UUID NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F06D39705E237E06 ON groups (name)');
        $this->addSql('COMMENT ON COLUMN groups.id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE contacts_groups ADD CONSTRAINT FK_3DA8886B719FB48E FOREIGN KEY (contacts_id) REFERENCES contacts (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE contacts_groups ADD CONSTRAINT FK_3DA8886BF373DCF FOREIGN KEY (groups_id) REFERENCES groups (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE contacts_groups DROP CONSTRAINT FK_3DA8886B719FB48E');
        $this->addSql('ALTER TABLE contacts_groups DROP CONSTRAINT FK_3DA8886BF373DCF');
        $this->addSql('DROP TABLE contacts');
        $this->addSql('DROP TABLE contacts_groups');
        $this->addSql('DROP TABLE groups');
    }
}
