<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231220113939 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE "loads_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "phones_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE loads (
                          id INT NOT NULL,
                          company_id INT NOT NULL,
                          user_id INT NOT NULL,
                          loading_type VARCHAR(100) NOT NULL, 
                          loading_first_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL,
                          loading_last_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL,
                          periodicity VARCHAR(100),
                          loading_start_time TIME WITHOUT TIME ZONE DEFAULT NULL,
                          loading_end_time TIME WITHOUT TIME ZONE DEFAULT NULL,
                          unloading_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL,
                          unloading_start_time TIME WITHOUT TIME ZONE DEFAULT NULL,
                          unloading_end_time TIME WITHOUT TIME ZONE DEFAULT NULL,
                          to_city_id INT NOT NULL, 
                          to_address VARCHAR(255) NOT NULL, 
                          to_latitude DOUBLE PRECISION DEFAULT NULL, 
                          to_longitude DOUBLE PRECISION DEFAULT NULL,
                          to_point geography(POINT, 4326) DEFAULT NULL, 
                          from_address VARCHAR(255) NOT NULL, 
                          from_city_id INT NOT NULL,
                          from_latitude DOUBLE PRECISION DEFAULT NULL, 
                          from_longitude DOUBLE PRECISION DEFAULT NULL, 
                          from_point geography(POINT, 4326) DEFAULT NULL,
                          weight REAL NOT NULL, 
                          volume REAL NOT NULL, 
                          load_type VARCHAR(50) DEFAULT \'ftl\',
                          temperature_from SMALLINT DEFAULT NULL,
                          temperature_to SMALLINT DEFAULT NULL,
                          price_type VARCHAR(50) NOT NULL, 
                          price_without_tax INT DEFAULT NULL, 
                          price_with_tax INT DEFAULT NULL, 
                          price_cash INT DEFAULT NULL, 
                          payment_on_card BOOLEAN DEFAULT FALSE,
                          hide_counter_offers BOOLEAN DEFAULT FALSE,
                          accept_bids_with_vat BOOLEAN DEFAULT FALSE,
                          accept_bids_without_vat BOOLEAN DEFAULT FALSE,
                          cargo_type INT NOT NULL, 
                          body_types JSON NOT NULL, 
                          truck_loading_types JSON NOT NULL, 
                          truck_unloading_types JSON NOT NULL, 
                          contact_ids JSON NOT NULL,
                          files JSON DEFAULT NULL,
                          note VARCHAR(1000) DEFAULT NULL,
                          created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, 
                          updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, 
                          PRIMARY KEY(id))'
        );
        $this->addSql('CREATE INDEX IDX_E52FFDEEA76ED395 ON loads (user_id)');
        $this->addSql('CREATE INDEX IDX_E52FFDEEA61ED395 ON loads (company_id)');
        $this->addSql('CREATE TABLE phones (id INT NOT NULL, user_id INT DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL, mobile_phone VARCHAR(255) DEFAULT NULL,PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E3282EF5A76ED395 ON phones (user_id)');
        $this->addSql('ALTER TABLE loads ADD CONSTRAINT FK_E52FFDEEA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE phones ADD CONSTRAINT FK_E3282EF5A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE loads ADD CONSTRAINT FK_E52FFDEEA61ED395 FOREIGN KEY (company_id) REFERENCES "companies" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE loads_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE phones_id_seq CASCADE');
        $this->addSql('ALTER TABLE loads DROP CONSTRAINT FK_E52FFDEEA76ED395');
        $this->addSql('ALTER TABLE loads DROP CONSTRAINT FK_E52FFDEEA61ED395');
        $this->addSql('ALTER TABLE phones DROP CONSTRAINT FK_E3282EF5A76ED395');
        $this->addSql('DROP TABLE loads');
        $this->addSql('DROP TABLE phones');
    }
}
