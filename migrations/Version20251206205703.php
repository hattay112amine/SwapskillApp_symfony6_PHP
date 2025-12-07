<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251206205703 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE exchange_proposal ADD receiver_id INT NOT NULL');
        $this->addSql('ALTER TABLE exchange_proposal ADD CONSTRAINT FK_251DED12CD53EDB6 FOREIGN KEY (receiver_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_251DED12CD53EDB6 ON exchange_proposal (receiver_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE exchange_proposal DROP FOREIGN KEY FK_251DED12CD53EDB6');
        $this->addSql('DROP INDEX IDX_251DED12CD53EDB6 ON exchange_proposal');
        $this->addSql('ALTER TABLE exchange_proposal DROP receiver_id');
    }
}
