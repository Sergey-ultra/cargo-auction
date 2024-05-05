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
        $this->addSql('CREATE SEQUENCE companies_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE companies (
                            id INT NOT NULL, 
                            name VARCHAR(255) NOT NULL, 
                            description VARCHAR(255),
                            ownership_id INT NOT NULL,
                            type_id INT NOT NULL,
                            user_id INT DEFAULT NULL,
                            city_id INT DEFAULT NULL,
                            created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, 
                            updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, 
                            PRIMARY KEY(id))
        ');
        $this->addSql('CREATE INDEX IDX_E13FFDEED96ED395 ON companies (user_id)');
        $this->addSql('CREATE INDEX IDX_E13FFDEDD96ED395 ON companies (city_id)');
        $this->addSql('ALTER TABLE companies ADD CONSTRAINT FK_153FFDEED96ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE companies ADD CONSTRAINT FK_153FFDEDD96ED395 FOREIGN KEY (city_id) REFERENCES "cities" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE companies_id_seq CASCADE');
        $this->addSql('ALTER TABLE companies DROP CONSTRAINT FK_153FFDEED96ED395');
        $this->addSql('ALTER TABLE companies DROP CONSTRAINT FK_153FFDEDD96ED395');
        $this->addSql('DROP TABLE companies');
    }
}
