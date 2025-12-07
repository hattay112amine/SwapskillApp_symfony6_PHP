<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251207013438 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event_registration ADD user_id INT DEFAULT NULL, CHANGE event_id event_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE event_registration ADD CONSTRAINT FK_8FBBAD54A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_8FBBAD54A76ED395 ON event_registration (user_id)');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FF1355373');
        $this->addSql('DROP INDEX IDX_B6BD307FF1355373 ON message');
        $this->addSql('ALTER TABLE message DROP exchange_proposal_id');
        $this->addSql('ALTER TABLE notification ADD content LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE rating DROP FOREIGN KEY FK_D88926222130303A');
        $this->addSql('ALTER TABLE rating DROP FOREIGN KEY FK_D889262229F6EE60');
        $this->addSql('ALTER TABLE rating CHANGE from_user_id from_user_id INT DEFAULT NULL, CHANGE to_user_id to_user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE rating ADD CONSTRAINT FK_D88926222130303A FOREIGN KEY (from_user_id) REFERENCES user (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE rating ADD CONSTRAINT FK_D889262229F6EE60 FOREIGN KEY (to_user_id) REFERENCES user (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE user DROP photo');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE notification DROP content');
        $this->addSql('ALTER TABLE user ADD photo VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE event_registration DROP FOREIGN KEY FK_8FBBAD54A76ED395');
        $this->addSql('DROP INDEX IDX_8FBBAD54A76ED395 ON event_registration');
        $this->addSql('ALTER TABLE event_registration DROP user_id, CHANGE event_id event_id INT NOT NULL');
        $this->addSql('ALTER TABLE rating DROP FOREIGN KEY FK_D88926222130303A');
        $this->addSql('ALTER TABLE rating DROP FOREIGN KEY FK_D889262229F6EE60');
        $this->addSql('ALTER TABLE rating CHANGE from_user_id from_user_id INT NOT NULL, CHANGE to_user_id to_user_id INT NOT NULL');
        $this->addSql('ALTER TABLE rating ADD CONSTRAINT FK_D88926222130303A FOREIGN KEY (from_user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE rating ADD CONSTRAINT FK_D889262229F6EE60 FOREIGN KEY (to_user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE message ADD exchange_proposal_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FF1355373 FOREIGN KEY (exchange_proposal_id) REFERENCES exchange_proposal (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_B6BD307FF1355373 ON message (exchange_proposal_id)');
    }
}
