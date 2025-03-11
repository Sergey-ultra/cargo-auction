<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240130063826 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE messages_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE messages (
                            id INT NOT NULL, 
                            message VARCHAR(255) NOT NULL, 
                            chat_id INT NOT NULL,
                            from_user_id INT NOT NULL,
                            to_user_id INT NOT NULL,
                            created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, 
                            updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, 
                            PRIMARY KEY(id))
        ');
        $this->addSql('CREATE INDEX IDX_E53FFDEEA86ED395 ON messages (chat_id)');
        $this->addSql('CREATE INDEX IDX_E53FFDEEA96ED395 ON messages (from_user_id)');
        $this->addSql('CREATE INDEX IDX_E53FFDEEA06ED395 ON messages (to_user_id)');
        $this->addSql('ALTER TABLE messages ADD CONSTRAINT FK_E53FFDEEA86ED395 FOREIGN KEY (chat_id) REFERENCES "chats" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE messages ADD CONSTRAINT FK_E53FFDEEA96ED395 FOREIGN KEY (from_user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE messages ADD CONSTRAINT FK_E53FFDEEA06ED395 FOREIGN KEY (to_user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE messages_id_seq CASCADE');
        $this->addSql('ALTER TABLE messages DROP CONSTRAINT FK_E53FFDEEA86ED395');
        $this->addSql('ALTER TABLE messages DROP CONSTRAINT FK_E53FFDEEA96ED395');
        $this->addSql('ALTER TABLE messages DROP CONSTRAINT FK_E53FFDEEA06ED395');
        $this->addSql('DROP TABLE messages');

    }
}
