<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210107164855 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE day_parameters (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', wt_parameter_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', am_start TIME DEFAULT NULL, am_pause_start TIME DEFAULT NULL, am_pause_end TIME DEFAULT NULL, am_end TIME DEFAULT NULL, pm_start TIME DEFAULT NULL, pm_pause_start TIME DEFAULT NULL, pm_pause_end TIME DEFAULT NULL, pm_end TIME DEFAULT NULL, am_pause_duration VARCHAR(255) DEFAULT NULL COMMENT \'(DC2Type:dateinterval)\', pm_pause_duration VARCHAR(255) DEFAULT NULL COMMENT \'(DC2Type:dateinterval)\', am_pm_pause_duration VARCHAR(255) DEFAULT NULL COMMENT \'(DC2Type:dateinterval)\', created_by VARCHAR(255) DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, timezone VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_2013C821903415F2 (wt_parameter_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE day_parameters ADD CONSTRAINT FK_2013C821903415F2 FOREIGN KEY (wt_parameter_id) REFERENCES wt_parameters (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE day_parameters');
    }
}
