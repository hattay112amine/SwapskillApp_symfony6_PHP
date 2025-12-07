<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251207002029 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F5D5C928D');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FF1355373');
        $this->addSql('DROP INDEX IDX_B6BD307FF1355373 ON message');
        $this->addSql('DROP INDEX IDX_B6BD307F5D5C928D ON message');
        $this->addSql('ALTER TABLE message DROP exchange_proposal_id, DROP reciever_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE message ADD exchange_proposal_id INT DEFAULT NULL, ADD reciever_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F5D5C928D FOREIGN KEY (reciever_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FF1355373 FOREIGN KEY (exchange_proposal_id) REFERENCES exchange_proposal (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_B6BD307FF1355373 ON message (exchange_proposal_id)');
        $this->addSql('CREATE INDEX IDX_B6BD307F5D5C928D ON message (reciever_id)');
    }
}
