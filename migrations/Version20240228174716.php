<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240228174716 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE transports_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE transports (
                           id INT NOT NULL,
                          user_id INT NOT NULL,
                          to_address VARCHAR(255) NOT NULL, 
                          to_latitude DOUBLE PRECISION DEFAULT NULL, 
                          to_longitude DOUBLE PRECISION DEFAULT NULL,
                          to_point geography(POINT, 4326) DEFAULT NULL, 
                          from_address VARCHAR(255) NOT NULL, 
                          from_latitude DOUBLE PRECISION DEFAULT NULL, 
                          from_longitude DOUBLE PRECISION DEFAULT NULL, 
                          from_point geography(POINT, 4326) DEFAULT NULL,
                          weight VARCHAR(255) NOT NULL, 
                          volume VARCHAR(255) NOT NULL, 
                          price_without_tax INT DEFAULT NULL, 
                          price_with_tax INT DEFAULT NULL, 
                          price_cash INT DEFAULT NULL, 
                          body_type INT NOT NULL, 
                          downloading_type INT NOT NULL,
                          created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, 
                          updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, 
                          PRIMARY KEY(id))');

        $this->addSql('CREATE INDEX IDX_E52FFDEEA66ED395 ON transports (user_id)');
        $this->addSql('ALTER TABLE transports ADD CONSTRAINT FK_E52FFDEEA66ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE transports_id_seq CASCADE');
        $this->addSql('ALTER TABLE transports DROP CONSTRAINT FK_E52FFDEEA66ED395');
        $this->addSql('DROP TABLE transports');
    }
}
