<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230426115621 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE aircraft (id INT NOT NULL, tail VARCHAR(50) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_13D967297C37B45D ON aircraft (tail)');
        $this->addSql('CREATE TABLE airports (
          id INT NOT NULL,
          code_iata VARCHAR(10) NOT NULL,
          code_icao VARCHAR(10) NOT NULL,
          country VARCHAR(2) NOT NULL,
          municipality VARCHAR(255) NOT NULL,
          name VARCHAR(255) NOT NULL,
          PRIMARY KEY(id)
        )');
        $this->addSql('CREATE TABLE flights (
          id INT NOT NULL,
          aircraft_id INT NOT NULL,
          airport1_id INT NOT NULL,
          airport2_id INT NOT NULL,
          takeoff TIMESTAMP(0)
          WITH
            TIME ZONE NOT NULL,
            landing TIMESTAMP(0)
          WITH
            TIME ZONE NOT NULL,
            aload INT NOT NULL,
            offload INT NOT NULL,
            PRIMARY KEY(id)
        )');
        $this->addSql('CREATE INDEX IDX_FC74B5EA846E2F5C ON flights (aircraft_id)');
        $this->addSql('CREATE INDEX IDX_FC74B5EA43FBF842 ON flights (airport1_id)');
        $this->addSql('CREATE INDEX IDX_FC74B5EA514E57AC ON flights (airport2_id)');
        $this->addSql('ALTER TABLE
          flights
        ADD
          CONSTRAINT FK_FC74B5EA846E2F5C FOREIGN KEY (aircraft_id) REFERENCES aircraft (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE
          flights
        ADD
          CONSTRAINT FK_FC74B5EA43FBF842 FOREIGN KEY (airport1_id) REFERENCES airports (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE
          flights
        ADD
          CONSTRAINT FK_FC74B5EA514E57AC FOREIGN KEY (airport2_id) REFERENCES airports (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE flights DROP CONSTRAINT FK_FC74B5EA846E2F5C');
        $this->addSql('ALTER TABLE flights DROP CONSTRAINT FK_FC74B5EA43FBF842');
        $this->addSql('ALTER TABLE flights DROP CONSTRAINT FK_FC74B5EA514E57AC');
        $this->addSql('DROP TABLE aircraft');
        $this->addSql('DROP TABLE airports');
        $this->addSql('DROP TABLE flights');
    }
}
