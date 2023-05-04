<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230503011944 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE team (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, country VARCHAR(255) NOT NULL, money_balance NUMERIC(10, 2) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE player (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, surname VARCHAR(255) NOT NULL, team_id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE transfer (id INT AUTO_INCREMENT NOT NULL, player_id INT NOT NULL, from_team_id INT NOT NULL, to_team_id INT NOT NULL, transfer_amount NUMERIC(10, 2) NOT NULL, transfer_date DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE player ADD CONSTRAINT FK_PLAYER_TEAM FOREIGN KEY (team_id) REFERENCES team (id)');
        $this->addSql('ALTER TABLE transfer ADD CONSTRAINT FK_TRANSFER_PLAYER FOREIGN KEY (player_id) REFERENCES player (id)');
        $this->addSql('ALTER TABLE transfer ADD CONSTRAINT FK_TRANSFER_FROM_TEAM FOREIGN KEY (from_team_id) REFERENCES team (id)');
        $this->addSql('ALTER TABLE transfer ADD CONSTRAINT FK_TRANSFER_TO_TEAM FOREIGN KEY (to_team_id) REFERENCES team (id)');
    }


    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE transfer DROP FOREIGN KEY FK_TRANSFER_TO_TEAM');
        $this->addSql('ALTER TABLE transfer DROP FOREIGN KEY FK_TRANSFER_FROM_TEAM');
        $this->addSql('ALTER TABLE transfer DROP FOREIGN KEY FK_TRANSFER_PLAYER');
        $this->addSql('ALTER TABLE player DROP FOREIGN KEY FK_PLAYER_TEAM');
        $this->addSql('DROP TABLE transfer');
        $this->addSql('DROP TABLE player');
        $this->addSql('DROP TABLE team');
    }

}
