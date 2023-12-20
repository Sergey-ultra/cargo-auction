<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231219182908 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE orders (id INT NOT NULL, user_id INT NOT NULL, to_address VARCHAR(255) NOT NULL, to_latitude DOUBLE PRECISION DEFAULT NULL, to_longitude DOUBLE PRECISION DEFAULT NULL, from_address VARCHAR(255) NOT NULL, from_latitude DOUBLE PRECISION DEFAULT NULL, from_longitude DOUBLE PRECISION DEFAULT NULL, weight VARCHAR(255) NOT NULL, volume VARCHAR(255) NOT NULL, is_agreed_price BOOLEAN DEFAULT false NOT NULL, price INT DEFAULT NULL, cargo_type INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E52FFDEEA76ED395 ON orders (user_id)');
        $this->addSql('CREATE TABLE phones (id INT NOT NULL, user_id INT DEFAULT NULL, phone VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E3282EF5A76ED395 ON phones (user_id)');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEEA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE phones ADD CONSTRAINT FK_E3282EF5A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE orders DROP CONSTRAINT FK_E52FFDEEA76ED395');
        $this->addSql('ALTER TABLE phones DROP CONSTRAINT FK_E3282EF5A76ED395');
        $this->addSql('DROP TABLE orders');
        $this->addSql('DROP TABLE phones');
    }
}
