<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210915143510 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8DAB69C8EF');
        $this->addSql('RENAME TABLE person TO user');
        $this->addSql('ALTER TABLE user ADD COLUMN roles JSON DEFAULT NULL, ADD COLUMN password VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DAB69C8EF FOREIGN KEY (written_by_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8DAB69C8EF');
        $this->addSql('CREATE TABLE person (id INT AUTO_INCREMENT NOT NULL, last_name VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`, first_name VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`, email VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE user');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8DAB69C8EF');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DAB69C8EF FOREIGN KEY (written_by_id) REFERENCES person (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
