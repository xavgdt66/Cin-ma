<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240327172139 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE movie_salle (movie_id INT NOT NULL, salle_id INT NOT NULL, INDEX IDX_30D564C08F93B6FC (movie_id), INDEX IDX_30D564C0DC304035 (salle_id), PRIMARY KEY(movie_id, salle_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE movie_salle ADD CONSTRAINT FK_30D564C08F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE movie_salle ADD CONSTRAINT FK_30D564C0DC304035 FOREIGN KEY (salle_id) REFERENCES salle (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE movie_salle DROP FOREIGN KEY FK_30D564C08F93B6FC');
        $this->addSql('ALTER TABLE movie_salle DROP FOREIGN KEY FK_30D564C0DC304035');
        $this->addSql('DROP TABLE movie_salle');
    }
}
