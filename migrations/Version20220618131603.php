<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220618131603 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE albums ADD article_id INT DEFAULT NULL, ADD legende VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE albums ADD CONSTRAINT FK_F4E2474F7294869C FOREIGN KEY (article_id) REFERENCES article (id)');
        $this->addSql('CREATE INDEX IDX_F4E2474F7294869C ON albums (article_id)');
        $this->addSql('ALTER TABLE article CHANGE description description VARCHAR(9999) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE albums DROP FOREIGN KEY FK_F4E2474F7294869C');
        $this->addSql('DROP INDEX IDX_F4E2474F7294869C ON albums');
        $this->addSql('ALTER TABLE albums DROP article_id, DROP legende');
        $this->addSql('ALTER TABLE article CHANGE description description VARCHAR(300) NOT NULL');
    }
}
