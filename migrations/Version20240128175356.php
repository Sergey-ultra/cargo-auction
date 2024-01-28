<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240128175356 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE chats_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE chats (
                            id INT NOT NULL, 
                            name VARCHAR(255) NOT NULL, 
                            description VARCHAR(255) NOT NULL, 
                            type VARCHAR(100) NOT NULL,
                            draft VARCHAR(255) NOT NULL,
                            unread BOOLEAN NOT NULL,
                            owner_id INT NOT NULL,
                            partner_id INT NOT NULL,
                            created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, 
                            updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, 
                            PRIMARY KEY(id))
        ');
        $this->addSql('CREATE INDEX IDX_E53FFDEEA76ED395 ON chats (owner_id)');
        $this->addSql('CREATE INDEX IDX_E54FFDEEA76ED395 ON chats (partner_id)');
        $this->addSql('ALTER TABLE chats ADD CONSTRAINT FK_E53FFDEEA76ED395 FOREIGN KEY (owner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE chats ADD CONSTRAINT FK_E54FFDEEA76ED395 FOREIGN KEY (partner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE chats_id_seq CASCADE');
        $this->addSql('ALTER TABLE chats DROP CONSTRAINT FK_E53FFDEEA76ED395');
        $this->addSql('ALTER TABLE chats DROP CONSTRAINT FK_E54FFDEEA76ED395');
        $this->addSql('DROP TABLE chats');

    }
}
