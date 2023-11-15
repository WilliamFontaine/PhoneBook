<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231115163606 extends AbstractMigration
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
        $this->addSql('ALTER TABLE contact_extended_fields ADD CONSTRAINT FK_ACB6CF23E7A1254A FOREIGN KEY (contact_id) REFERENCES contacts (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE contact_extended_fields_id_seq CASCADE');
        $this->addSql('ALTER TABLE contact_extended_fields DROP CONSTRAINT FK_ACB6CF23E7A1254A');
        $this->addSql('DROP TABLE contact_extended_fields');
    }
}
