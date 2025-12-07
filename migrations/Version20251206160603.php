<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251206160603 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE exchange_proposal ADD requester_id INT NOT NULL, CHANGE created_at created_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE exchange_proposal ADD CONSTRAINT FK_251DED12ED442CF4 FOREIGN KEY (requester_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_251DED12ED442CF4 ON exchange_proposal (requester_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE exchange_proposal DROP FOREIGN KEY FK_251DED12ED442CF4');
        $this->addSql('DROP INDEX IDX_251DED12ED442CF4 ON exchange_proposal');
        $this->addSql('ALTER TABLE exchange_proposal DROP requester_id, CHANGE created_at created_at DATETIME DEFAULT NULL');
    }
}
