<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251020191522 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE exchange_proposal (id INT AUTO_INCREMENT NOT NULL, offered_skill_id INT NOT NULL, requested_skill_id INT NOT NULL, proposal VARCHAR(255) DEFAULT NULL, status VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL, INDEX IDX_251DED12F0A58CBC (offered_skill_id), INDEX IDX_251DED1224F688D2 (requested_skill_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE exchange_proposal ADD CONSTRAINT FK_251DED12F0A58CBC FOREIGN KEY (offered_skill_id) REFERENCES skill (id)');
        $this->addSql('ALTER TABLE exchange_proposal ADD CONSTRAINT FK_251DED1224F688D2 FOREIGN KEY (requested_skill_id) REFERENCES skill (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE exchange_proposal DROP FOREIGN KEY FK_251DED12F0A58CBC');
        $this->addSql('ALTER TABLE exchange_proposal DROP FOREIGN KEY FK_251DED1224F688D2');
        $this->addSql('DROP TABLE exchange_proposal');
    }
}
