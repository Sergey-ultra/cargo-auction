<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240202063600 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE filters_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE filters (
                            id INT NOT NULL, 
                            name VARCHAR(255) NOT NULL, 
                            type VARCHAR(255) NOT NULL, 
                            filter JSON NOT NULL,
                            user_id INT NOT NULL,
                            created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, 
                            updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, 
                            PRIMARY KEY(id))
        ');
        $this->addSql('CREATE INDEX IDX_E53FFDEED96ED395 ON filters (user_id)');
        $this->addSql('ALTER TABLE filters ADD CONSTRAINT FK_E53FFDEED96ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE filters_id_seq CASCADE');
        $this->addSql('ALTER TABLE messages DROP CONSTRAINT FK_E53FFDEED96ED395');
        $this->addSql('DROP TABLE filters');

    }
}
