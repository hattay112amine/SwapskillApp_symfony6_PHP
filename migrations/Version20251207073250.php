<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251207073250 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7876C4DDA');
        $this->addSql('ALTER TABLE event CHANGE organizer_id organizer_id INT NOT NULL');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7876C4DDA FOREIGN KEY (organizer_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE event_registration ADD user_id INT DEFAULT NULL, CHANGE event_id event_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE event_registration ADD CONSTRAINT FK_8FBBAD54A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_8FBBAD54A76ED395 ON event_registration (user_id)');
        $this->addSql('ALTER TABLE exchange_proposal ADD requester_id INT NOT NULL, ADD receiver_id INT NOT NULL, CHANGE created_at created_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE exchange_proposal ADD CONSTRAINT FK_251DED12ED442CF4 FOREIGN KEY (requester_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE exchange_proposal ADD CONSTRAINT FK_251DED12CD53EDB6 FOREIGN KEY (receiver_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_251DED12ED442CF4 ON exchange_proposal (requester_id)');
        $this->addSql('CREATE INDEX IDX_251DED12CD53EDB6 ON exchange_proposal (receiver_id)');
        $this->addSql('ALTER TABLE message CHANGE exchange_proposal_id exchange_proposal_id INT NOT NULL');
        $this->addSql('ALTER TABLE notification ADD user_id INT NOT NULL, ADD proposal_id INT DEFAULT NULL, ADD content LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CAA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CAF4792058 FOREIGN KEY (proposal_id) REFERENCES exchange_proposal (id)');
        $this->addSql('CREATE INDEX IDX_BF5476CAA76ED395 ON notification (user_id)');
        $this->addSql('CREATE INDEX IDX_BF5476CAF4792058 ON notification (proposal_id)');
        $this->addSql('ALTER TABLE rating ADD exchange_proposal_id INT NOT NULL');
        $this->addSql('ALTER TABLE rating ADD CONSTRAINT FK_D8892622F1355373 FOREIGN KEY (exchange_proposal_id) REFERENCES exchange_proposal (id)');
        $this->addSql('CREATE INDEX IDX_D8892622F1355373 ON rating (exchange_proposal_id)');
        $this->addSql('ALTER TABLE skill ADD owner_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE skill ADD CONSTRAINT FK_5E3DE4777E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_5E3DE4777E3C61F9 ON skill (owner_id)');
        $this->addSql('ALTER TABLE user DROP offered_skill, DROP requested_skill');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7876C4DDA');
        $this->addSql('ALTER TABLE event CHANGE organizer_id organizer_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7876C4DDA FOREIGN KEY (organizer_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE SET NULL');
        $this->addSql('ALTER TABLE user ADD offered_skill LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', ADD requested_skill LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\'');
        $this->addSql('ALTER TABLE skill DROP FOREIGN KEY FK_5E3DE4777E3C61F9');
        $this->addSql('DROP INDEX IDX_5E3DE4777E3C61F9 ON skill');
        $this->addSql('ALTER TABLE skill DROP owner_id');
        $this->addSql('ALTER TABLE event_registration DROP FOREIGN KEY FK_8FBBAD54A76ED395');
        $this->addSql('DROP INDEX IDX_8FBBAD54A76ED395 ON event_registration');
        $this->addSql('ALTER TABLE event_registration DROP user_id, CHANGE event_id event_id INT NOT NULL');
        $this->addSql('ALTER TABLE rating DROP FOREIGN KEY FK_D8892622F1355373');
        $this->addSql('DROP INDEX IDX_D8892622F1355373 ON rating');
        $this->addSql('ALTER TABLE rating DROP exchange_proposal_id');
        $this->addSql('ALTER TABLE exchange_proposal DROP FOREIGN KEY FK_251DED12ED442CF4');
        $this->addSql('ALTER TABLE exchange_proposal DROP FOREIGN KEY FK_251DED12CD53EDB6');
        $this->addSql('DROP INDEX IDX_251DED12ED442CF4 ON exchange_proposal');
        $this->addSql('DROP INDEX IDX_251DED12CD53EDB6 ON exchange_proposal');
        $this->addSql('ALTER TABLE exchange_proposal DROP requester_id, DROP receiver_id, CHANGE created_at created_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE message CHANGE exchange_proposal_id exchange_proposal_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CAA76ED395');
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CAF4792058');
        $this->addSql('DROP INDEX IDX_BF5476CAA76ED395 ON notification');
        $this->addSql('DROP INDEX IDX_BF5476CAF4792058 ON notification');
        $this->addSql('ALTER TABLE notification DROP user_id, DROP proposal_id, DROP content');
    }
}
