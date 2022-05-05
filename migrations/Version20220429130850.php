<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220429130850 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bd (id INT AUTO_INCREMENT NOT NULL, auteur_id INT NOT NULL, titre VARCHAR(80) NOT NULL, description LONGTEXT NOT NULL, date_publication DATE NOT NULL, INDEX IDX_5CCDBE9B60BB6FE6 (auteur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bd_genre (bd_id INT NOT NULL, genre_id INT NOT NULL, INDEX IDX_30BF6E9E894AF46 (bd_id), INDEX IDX_30BF6E9E4296D31F (genre_id), PRIMARY KEY(bd_id, genre_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bd ADD CONSTRAINT FK_5CCDBE9B60BB6FE6 FOREIGN KEY (auteur_id) REFERENCES auteur (id)');
        $this->addSql('ALTER TABLE bd_genre ADD CONSTRAINT FK_30BF6E9E894AF46 FOREIGN KEY (bd_id) REFERENCES bd (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bd_genre ADD CONSTRAINT FK_30BF6E9E4296D31F FOREIGN KEY (genre_id) REFERENCES genre (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cathegorie ADD b_d_id INT NOT NULL');
        $this->addSql('ALTER TABLE cathegorie ADD CONSTRAINT FK_CCFDF4EC63F92B9E FOREIGN KEY (b_d_id) REFERENCES bd (id)');
        $this->addSql('CREATE INDEX IDX_CCFDF4EC63F92B9E ON cathegorie (b_d_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bd_genre DROP FOREIGN KEY FK_30BF6E9E894AF46');
        $this->addSql('ALTER TABLE cathegorie DROP FOREIGN KEY FK_CCFDF4EC63F92B9E');
        $this->addSql('DROP TABLE bd');
        $this->addSql('DROP TABLE bd_genre');
        $this->addSql('DROP INDEX IDX_CCFDF4EC63F92B9E ON cathegorie');
        $this->addSql('ALTER TABLE cathegorie DROP b_d_id');
    }
}
