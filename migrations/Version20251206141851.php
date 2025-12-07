<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251206141851 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, organizer_id INT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, type VARCHAR(255) NOT NULL, date DATETIME DEFAULT NULL, location VARCHAR(255) DEFAULT NULL, price DOUBLE PRECISION DEFAULT NULL, capacity INT DEFAULT NULL, created_at DATETIME DEFAULT NULL, INDEX IDX_3BAE0AA7876C4DDA (organizer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event_registration (id INT AUTO_INCREMENT NOT NULL, participant_id INT NOT NULL, event_id INT NOT NULL, registered_at DATETIME NOT NULL, status VARCHAR(20) NOT NULL, INDEX IDX_8FBBAD549D1C3019 (participant_id), INDEX IDX_8FBBAD5471F7E88B (event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE exchange_proposal (id INT AUTO_INCREMENT NOT NULL, offered_skill_id INT NOT NULL, requested_skill_id INT NOT NULL, proposal VARCHAR(255) DEFAULT NULL, status VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL, INDEX IDX_251DED12F0A58CBC (offered_skill_id), INDEX IDX_251DED1224F688D2 (requested_skill_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, sender_id INT NOT NULL, receiver_id INT NOT NULL, exchange_proposal_id INT DEFAULT NULL, content VARCHAR(255) DEFAULT NULL, is_read TINYINT(1) DEFAULT NULL, created_at DATETIME DEFAULT NULL, INDEX IDX_B6BD307FF624B39D (sender_id), INDEX IDX_B6BD307FCD53EDB6 (receiver_id), INDEX IDX_B6BD307FF1355373 (exchange_proposal_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notification (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) DEFAULT NULL, is_read TINYINT(1) DEFAULT NULL, created_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rating (id INT AUTO_INCREMENT NOT NULL, from_user_id INT NOT NULL, to_user_id INT NOT NULL, score INT DEFAULT NULL, comment VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL, INDEX IDX_D88926222130303A (from_user_id), INDEX IDX_D889262229F6EE60 (to_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE skill (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, category VARCHAR(255) DEFAULT NULL, level VARCHAR(255) DEFAULT NULL, availability VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, roles LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', name VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) NOT NULL, bio VARCHAR(255) DEFAULT NULL, adress VARCHAR(255) DEFAULT NULL, status TINYINT(1) DEFAULT NULL, offered_skill LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', requested_skill LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', created_at DATETIME DEFAULT NULL, google_id VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7876C4DDA FOREIGN KEY (organizer_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE event_registration ADD CONSTRAINT FK_8FBBAD549D1C3019 FOREIGN KEY (participant_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE event_registration ADD CONSTRAINT FK_8FBBAD5471F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE exchange_proposal ADD CONSTRAINT FK_251DED12F0A58CBC FOREIGN KEY (offered_skill_id) REFERENCES skill (id)');
        $this->addSql('ALTER TABLE exchange_proposal ADD CONSTRAINT FK_251DED1224F688D2 FOREIGN KEY (requested_skill_id) REFERENCES skill (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FF624B39D FOREIGN KEY (sender_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FCD53EDB6 FOREIGN KEY (receiver_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FF1355373 FOREIGN KEY (exchange_proposal_id) REFERENCES exchange_proposal (id)');
        $this->addSql('ALTER TABLE rating ADD CONSTRAINT FK_D88926222130303A FOREIGN KEY (from_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE rating ADD CONSTRAINT FK_D889262229F6EE60 FOREIGN KEY (to_user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7876C4DDA');
        $this->addSql('ALTER TABLE event_registration DROP FOREIGN KEY FK_8FBBAD549D1C3019');
        $this->addSql('ALTER TABLE event_registration DROP FOREIGN KEY FK_8FBBAD5471F7E88B');
        $this->addSql('ALTER TABLE exchange_proposal DROP FOREIGN KEY FK_251DED12F0A58CBC');
        $this->addSql('ALTER TABLE exchange_proposal DROP FOREIGN KEY FK_251DED1224F688D2');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FF624B39D');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FCD53EDB6');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FF1355373');
        $this->addSql('ALTER TABLE rating DROP FOREIGN KEY FK_D88926222130303A');
        $this->addSql('ALTER TABLE rating DROP FOREIGN KEY FK_D889262229F6EE60');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE event_registration');
        $this->addSql('DROP TABLE exchange_proposal');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE notification');
        $this->addSql('DROP TABLE rating');
        $this->addSql('DROP TABLE skill');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
