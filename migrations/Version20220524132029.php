<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220524132029 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE link (id INT AUTO_INCREMENT NOT NULL, github VARCHAR(255) DEFAULT NULL, web VARCHAR(255) DEFAULT NULL, video VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE article ADD links_id INT DEFAULT NULL, CHANGE description description VARCHAR(300) NOT NULL');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66C0DE588D FOREIGN KEY (links_id) REFERENCES link (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_23A0E66C0DE588D ON article (links_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66C0DE588D');
        $this->addSql('DROP TABLE link');
        $this->addSql('DROP INDEX UNIQ_23A0E66C0DE588D ON article');
        $this->addSql('ALTER TABLE article DROP links_id, CHANGE description description VARCHAR(9999) NOT NULL');
    }
}
