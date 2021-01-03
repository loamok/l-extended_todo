<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210103100034 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE wt_parameters ADD base_lunch_break_duration VARCHAR(255) DEFAULT NULL COMMENT \'(DC2Type:dateinterval)\', ADD extended_lunch_break_duration VARCHAR(255) DEFAULT NULL COMMENT \'(DC2Type:dateinterval)\', ADD shorted_lunch_break_duration VARCHAR(255) DEFAULT NULL COMMENT \'(DC2Type:dateinterval)\', ADD base_work_day_hours_duration VARCHAR(255) DEFAULT NULL COMMENT \'(DC2Type:dateinterval)\', ADD extended_work_day_hours_duration VARCHAR(255) DEFAULT NULL COMMENT \'(DC2Type:dateinterval)\', ADD shorted_work_day_hours_duration VARCHAR(255) DEFAULT NULL COMMENT \'(DC2Type:dateinterval)\', ADD base_total_day_breaks_duration VARCHAR(255) DEFAULT NULL COMMENT \'(DC2Type:dateinterval)\', ADD extended_total_day_breaks_duration VARCHAR(255) DEFAULT NULL COMMENT \'(DC2Type:dateinterval)\', ADD shorted_total_day_breaks_duration VARCHAR(255) DEFAULT NULL COMMENT \'(DC2Type:dateinterval)\', ADD annual_toil_days_number INT DEFAULT NULL, ADD annual_holiday_days_number INT DEFAULT NULL, ADD no_work_before TIME DEFAULT NULL, ADD no_work_after TIME DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE wt_parameters DROP base_lunch_break_duration, DROP extended_lunch_break_duration, DROP shorted_lunch_break_duration, DROP base_work_day_hours_duration, DROP extended_work_day_hours_duration, DROP shorted_work_day_hours_duration, DROP base_total_day_breaks_duration, DROP extended_total_day_breaks_duration, DROP shorted_total_day_breaks_duration, DROP annual_toil_days_number, DROP annual_holiday_days_number, DROP no_work_before, DROP no_work_after');
    }
}
