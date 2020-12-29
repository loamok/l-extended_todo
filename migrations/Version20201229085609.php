<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201229085609 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ag_type (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', code VARCHAR(255) NOT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE agenda (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', tz_id INT NOT NULL, type_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(255) NOT NULL, created_by VARCHAR(255) DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, timezone VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_2CEDC87757F2EDC8 (tz_id), INDEX IDX_2CEDC877C54C8C93 (type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE delegation (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', agenda_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', user_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', owner_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', delegation_type_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', created_by VARCHAR(255) DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, timezone VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_292F436DEA67784A (agenda_id), INDEX IDX_292F436DA76ED395 (user_id), INDEX IDX_292F436D7E3C61F9 (owner_id), INDEX IDX_292F436DB5224DF6 (delegation_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE delegation_type (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', code VARCHAR(255) NOT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE delegation_type_rights (delegation_type_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', rights_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_15597668B5224DF6 (delegation_type_id), INDEX IDX_15597668B196EE6E (rights_id), PRIMARY KEY(delegation_type_id, rights_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', agenda_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', created_by VARCHAR(255) DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, timezone VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, end_at DATETIME NOT NULL, duration VARCHAR(255) NOT NULL COMMENT \'(DC2Type:dateinterval)\', start_at DATETIME NOT NULL, location VARCHAR(255) DEFAULT NULL, summary VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, geo POINT DEFAULT NULL COMMENT \'(DC2Type:geogpoint)\', INDEX IDX_3BAE0AA7EA67784A (agenda_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ext_log_entries (id INT AUTO_INCREMENT NOT NULL, action VARCHAR(8) NOT NULL, logged_at DATETIME NOT NULL, object_id VARCHAR(64) DEFAULT NULL, object_class VARCHAR(191) NOT NULL, version INT NOT NULL, data LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', username VARCHAR(191) DEFAULT NULL, INDEX log_class_lookup_idx (object_class), INDEX log_date_lookup_idx (logged_at), INDEX log_user_lookup_idx (username), INDEX log_version_lookup_idx (object_id, object_class, version), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB ROW_FORMAT = DYNAMIC');
        $this->addSql('CREATE TABLE ext_translations (id INT AUTO_INCREMENT NOT NULL, locale VARCHAR(8) NOT NULL, object_class VARCHAR(191) NOT NULL, field VARCHAR(32) NOT NULL, foreign_key VARCHAR(64) NOT NULL, content LONGTEXT DEFAULT NULL, INDEX translations_lookup_idx (locale, object_class, foreign_key), UNIQUE INDEX lookup_unique_idx (locale, object_class, field, foreign_key), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB ROW_FORMAT = DYNAMIC');
        $this->addSql('CREATE TABLE journal (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', agenda_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', summary VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, created_by VARCHAR(255) DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, timezone VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, start_at DATETIME NOT NULL, INDEX IDX_C1A7E74DEA67784A (agenda_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE personalized_delegation_rights (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', delegation_type_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', delegation_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', rights_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', created_by VARCHAR(255) DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, timezone VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_5484E804B5224DF6 (delegation_type_id), INDEX IDX_5484E80456CBBCF5 (delegation_id), INDEX IDX_5484E804B196EE6E (rights_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rights (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', code VARCHAR(255) NOT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role_globals (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', role VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role_globals_rights (role_globals_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', rights_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_5487B24E8FD0D5DB (role_globals_id), INDEX IDX_5487B24EB196EE6E (rights_id), PRIMARY KEY(role_globals_id, rights_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE timezone (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, label VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE todo (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', agenda_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', completed TINYINT(1) NOT NULL, percent INT NOT NULL, priority SMALLINT NOT NULL, summary VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, location VARCHAR(255) DEFAULT NULL, geo POINT DEFAULT NULL COMMENT \'(DC2Type:geogpoint)\', created_by VARCHAR(255) DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, timezone VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, end_at DATETIME NOT NULL, duration VARCHAR(255) NOT NULL COMMENT \'(DC2Type:dateinterval)\', start_at DATETIME NOT NULL, INDEX IDX_5A0EB6A0EA67784A (agenda_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE agenda ADD CONSTRAINT FK_2CEDC87757F2EDC8 FOREIGN KEY (tz_id) REFERENCES timezone (id)');
        $this->addSql('ALTER TABLE agenda ADD CONSTRAINT FK_2CEDC877C54C8C93 FOREIGN KEY (type_id) REFERENCES ag_type (id)');
        $this->addSql('ALTER TABLE delegation ADD CONSTRAINT FK_292F436DEA67784A FOREIGN KEY (agenda_id) REFERENCES agenda (id)');
        $this->addSql('ALTER TABLE delegation ADD CONSTRAINT FK_292F436DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE delegation ADD CONSTRAINT FK_292F436D7E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE delegation ADD CONSTRAINT FK_292F436DB5224DF6 FOREIGN KEY (delegation_type_id) REFERENCES delegation_type (id)');
        $this->addSql('ALTER TABLE delegation_type_rights ADD CONSTRAINT FK_15597668B5224DF6 FOREIGN KEY (delegation_type_id) REFERENCES delegation_type (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE delegation_type_rights ADD CONSTRAINT FK_15597668B196EE6E FOREIGN KEY (rights_id) REFERENCES rights (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7EA67784A FOREIGN KEY (agenda_id) REFERENCES agenda (id)');
        $this->addSql('ALTER TABLE journal ADD CONSTRAINT FK_C1A7E74DEA67784A FOREIGN KEY (agenda_id) REFERENCES agenda (id)');
        $this->addSql('ALTER TABLE personalized_delegation_rights ADD CONSTRAINT FK_5484E804B5224DF6 FOREIGN KEY (delegation_type_id) REFERENCES delegation_type (id)');
        $this->addSql('ALTER TABLE personalized_delegation_rights ADD CONSTRAINT FK_5484E80456CBBCF5 FOREIGN KEY (delegation_id) REFERENCES delegation (id)');
        $this->addSql('ALTER TABLE personalized_delegation_rights ADD CONSTRAINT FK_5484E804B196EE6E FOREIGN KEY (rights_id) REFERENCES rights (id)');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE role_globals_rights ADD CONSTRAINT FK_5487B24E8FD0D5DB FOREIGN KEY (role_globals_id) REFERENCES role_globals (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE role_globals_rights ADD CONSTRAINT FK_5487B24EB196EE6E FOREIGN KEY (rights_id) REFERENCES rights (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE todo ADD CONSTRAINT FK_5A0EB6A0EA67784A FOREIGN KEY (agenda_id) REFERENCES agenda (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE agenda DROP FOREIGN KEY FK_2CEDC877C54C8C93');
        $this->addSql('ALTER TABLE delegation DROP FOREIGN KEY FK_292F436DEA67784A');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7EA67784A');
        $this->addSql('ALTER TABLE journal DROP FOREIGN KEY FK_C1A7E74DEA67784A');
        $this->addSql('ALTER TABLE todo DROP FOREIGN KEY FK_5A0EB6A0EA67784A');
        $this->addSql('ALTER TABLE personalized_delegation_rights DROP FOREIGN KEY FK_5484E80456CBBCF5');
        $this->addSql('ALTER TABLE delegation DROP FOREIGN KEY FK_292F436DB5224DF6');
        $this->addSql('ALTER TABLE delegation_type_rights DROP FOREIGN KEY FK_15597668B5224DF6');
        $this->addSql('ALTER TABLE personalized_delegation_rights DROP FOREIGN KEY FK_5484E804B5224DF6');
        $this->addSql('ALTER TABLE delegation_type_rights DROP FOREIGN KEY FK_15597668B196EE6E');
        $this->addSql('ALTER TABLE personalized_delegation_rights DROP FOREIGN KEY FK_5484E804B196EE6E');
        $this->addSql('ALTER TABLE role_globals_rights DROP FOREIGN KEY FK_5487B24EB196EE6E');
        $this->addSql('ALTER TABLE role_globals_rights DROP FOREIGN KEY FK_5487B24E8FD0D5DB');
        $this->addSql('ALTER TABLE agenda DROP FOREIGN KEY FK_2CEDC87757F2EDC8');
        $this->addSql('ALTER TABLE delegation DROP FOREIGN KEY FK_292F436DA76ED395');
        $this->addSql('ALTER TABLE delegation DROP FOREIGN KEY FK_292F436D7E3C61F9');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('DROP TABLE ag_type');
        $this->addSql('DROP TABLE agenda');
        $this->addSql('DROP TABLE delegation');
        $this->addSql('DROP TABLE delegation_type');
        $this->addSql('DROP TABLE delegation_type_rights');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE ext_log_entries');
        $this->addSql('DROP TABLE ext_translations');
        $this->addSql('DROP TABLE journal');
        $this->addSql('DROP TABLE personalized_delegation_rights');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE rights');
        $this->addSql('DROP TABLE role_globals');
        $this->addSql('DROP TABLE role_globals_rights');
        $this->addSql('DROP TABLE timezone');
        $this->addSql('DROP TABLE todo');
        $this->addSql('DROP TABLE user');
    }
}
