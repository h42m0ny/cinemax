<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200127212821 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE serie (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, note DOUBLE PRECISION DEFAULT NULL, summary LONGTEXT NOT NULL, image VARCHAR(255) DEFAULT NULL, first_air_date DATE DEFAULT NULL, im_dbid INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE genre_serie (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE genre_serie_serie (genre_serie_id INT NOT NULL, serie_id INT NOT NULL, INDEX IDX_294E1A6B6A1E7700 (genre_serie_id), INDEX IDX_294E1A6BD94388BD (serie_id), PRIMARY KEY(genre_serie_id, serie_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE genre_serie_serie ADD CONSTRAINT FK_294E1A6B6A1E7700 FOREIGN KEY (genre_serie_id) REFERENCES genre_serie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE genre_serie_serie ADD CONSTRAINT FK_294E1A6BD94388BD FOREIGN KEY (serie_id) REFERENCES serie (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE genre_serie_serie DROP FOREIGN KEY FK_294E1A6BD94388BD');
        $this->addSql('ALTER TABLE genre_serie_serie DROP FOREIGN KEY FK_294E1A6B6A1E7700');
        $this->addSql('DROP TABLE serie');
        $this->addSql('DROP TABLE genre_serie');
        $this->addSql('DROP TABLE genre_serie_serie');
    }
}
