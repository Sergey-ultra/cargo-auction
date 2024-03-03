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
                          company_id INT NOT NULL,
                          from_city_id INT DEFAULT NULL, 
                          from_name VARCHAR(200) DEFAULT NULL,
                          to_city_id INT DEFAULT NULL,
                          to_name VARCHAR(200) DEFAULT NULL,
                          weight DOUBLE PRECISION NOT NULL, 
                          volume DOUBLE PRECISION NOT NULL, 
                          price_without_tax INT DEFAULT NULL, 
                          price_with_tax INT DEFAULT NULL, 
                          price_cash INT DEFAULT NULL, 
                          body_type INT NOT NULL, 
                          downloading_type INT DEFAULT NULL,
                          created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, 
                          updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, 
                          PRIMARY KEY(id))');

        $this->addSql('CREATE INDEX IDX_E52FFDEEA66ED395 ON transports (company_id)');
        $this->addSql('ALTER TABLE transports ADD CONSTRAINT FK_E52FFDEEA66ED395 FOREIGN KEY (company_id) REFERENCES "companies" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
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
