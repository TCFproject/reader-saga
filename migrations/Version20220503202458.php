<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220503202458 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bd ADD categorie_id INT NOT NULL');
        $this->addSql('ALTER TABLE bd ADD CONSTRAINT FK_5CCDBE9BBCF5E72D FOREIGN KEY (categorie_id) REFERENCES cathegorie (id)');
        $this->addSql('CREATE INDEX IDX_5CCDBE9BBCF5E72D ON bd (categorie_id)');
        $this->addSql('ALTER TABLE cathegorie DROP FOREIGN KEY FK_CCFDF4EC63F92B9E');
        $this->addSql('DROP INDEX IDX_CCFDF4EC63F92B9E ON cathegorie');
        $this->addSql('ALTER TABLE cathegorie DROP b_d_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bd DROP FOREIGN KEY FK_5CCDBE9BBCF5E72D');
        $this->addSql('DROP INDEX IDX_5CCDBE9BBCF5E72D ON bd');
        $this->addSql('ALTER TABLE bd DROP categorie_id');
        $this->addSql('ALTER TABLE cathegorie ADD b_d_id INT NOT NULL');
        $this->addSql('ALTER TABLE cathegorie ADD CONSTRAINT FK_CCFDF4EC63F92B9E FOREIGN KEY (b_d_id) REFERENCES bd (id)');
        $this->addSql('CREATE INDEX IDX_CCFDF4EC63F92B9E ON cathegorie (b_d_id)');
    }
}
