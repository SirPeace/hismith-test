<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210706084511 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE enclosure_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE news_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE enclosure (id INT NOT NULL, url VARCHAR(2000) NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E0F73063F47645AE ON enclosure (url)');
        $this->addSql('CREATE TABLE news (id INT NOT NULL, title VARCHAR(255) NOT NULL, link VARCHAR(2000) NOT NULL, description TEXT NOT NULL, pub_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, author VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN news.pub_date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE news_enclosure (news_id INT NOT NULL, enclosure_id INT NOT NULL, PRIMARY KEY(news_id, enclosure_id))');
        $this->addSql('CREATE INDEX IDX_FCD26FA2B5A459A0 ON news_enclosure (news_id)');
        $this->addSql('CREATE INDEX IDX_FCD26FA2D04FE1E5 ON news_enclosure (enclosure_id)');
        $this->addSql('ALTER TABLE news_enclosure ADD CONSTRAINT FK_FCD26FA2B5A459A0 FOREIGN KEY (news_id) REFERENCES news (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE news_enclosure ADD CONSTRAINT FK_FCD26FA2D04FE1E5 FOREIGN KEY (enclosure_id) REFERENCES enclosure (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE news_enclosure DROP CONSTRAINT FK_FCD26FA2D04FE1E5');
        $this->addSql('ALTER TABLE news_enclosure DROP CONSTRAINT FK_FCD26FA2B5A459A0');
        $this->addSql('DROP SEQUENCE enclosure_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE news_id_seq CASCADE');
        $this->addSql('DROP TABLE enclosure');
        $this->addSql('DROP TABLE news');
        $this->addSql('DROP TABLE news_enclosure');
    }
}
