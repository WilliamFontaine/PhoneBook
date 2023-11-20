<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231120212134 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE contact_extended_fields_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE contact_extended_fields (id INT NOT NULL, contact_id UUID NOT NULL, field_name VARCHAR(255) NOT NULL, field_value VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_ACB6CF23E7A1254A ON contact_extended_fields (contact_id)');
        $this->addSql('COMMENT ON COLUMN contact_extended_fields.contact_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE contacts (id UUID NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, image_name VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_33401573444F97DD ON contacts (phone)');
        $this->addSql('COMMENT ON COLUMN contacts.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN contacts.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN contacts.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE contacts_groups (contacts_id UUID NOT NULL, groups_id UUID NOT NULL, PRIMARY KEY(contacts_id, groups_id))');
        $this->addSql('CREATE INDEX IDX_3DA8886B719FB48E ON contacts_groups (contacts_id)');
        $this->addSql('CREATE INDEX IDX_3DA8886BF373DCF ON contacts_groups (groups_id)');
        $this->addSql('COMMENT ON COLUMN contacts_groups.contacts_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN contacts_groups.groups_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE groups (id UUID NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F06D39705E237E06 ON groups (name)');
        $this->addSql('COMMENT ON COLUMN groups.id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE contact_extended_fields ADD CONSTRAINT FK_ACB6CF23E7A1254A FOREIGN KEY (contact_id) REFERENCES contacts (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE contacts_groups ADD CONSTRAINT FK_3DA8886B719FB48E FOREIGN KEY (contacts_id) REFERENCES contacts (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE contacts_groups ADD CONSTRAINT FK_3DA8886BF373DCF FOREIGN KEY (groups_id) REFERENCES groups (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE contact_extended_fields_id_seq CASCADE');
        $this->addSql('ALTER TABLE contact_extended_fields DROP CONSTRAINT FK_ACB6CF23E7A1254A');
        $this->addSql('ALTER TABLE contacts_groups DROP CONSTRAINT FK_3DA8886B719FB48E');
        $this->addSql('ALTER TABLE contacts_groups DROP CONSTRAINT FK_3DA8886BF373DCF');
        $this->addSql('DROP TABLE contact_extended_fields');
        $this->addSql('DROP TABLE contacts');
        $this->addSql('DROP TABLE contacts_groups');
        $this->addSql('DROP TABLE groups');
    }
}
