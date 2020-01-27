<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200127194626 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE movie (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, note DOUBLE PRECISION NOT NULL, im_dbid INT NOT NULL, release_date DATE NOT NULL, summary LONGTEXT NOT NULL, image VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE movie_genre_movie (movie_id INT NOT NULL, genre_movie_id INT NOT NULL, INDEX IDX_8FDE615D8F93B6FC (movie_id), INDEX IDX_8FDE615D3CCE4941 (genre_movie_id), PRIMARY KEY(movie_id, genre_movie_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE genre_movie (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE movie_genre_movie ADD CONSTRAINT FK_8FDE615D8F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE movie_genre_movie ADD CONSTRAINT FK_8FDE615D3CCE4941 FOREIGN KEY (genre_movie_id) REFERENCES genre_movie (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE movie_genre_movie DROP FOREIGN KEY FK_8FDE615D8F93B6FC');
        $this->addSql('ALTER TABLE movie_genre_movie DROP FOREIGN KEY FK_8FDE615D3CCE4941');
        $this->addSql('DROP TABLE movie');
        $this->addSql('DROP TABLE movie_genre_movie');
        $this->addSql('DROP TABLE genre_movie');
    }
}
