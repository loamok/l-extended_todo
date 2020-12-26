<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201226161051 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE delegation (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', agenda_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', user_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', owner_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', delegation_type_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', created_by VARCHAR(255) DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, timezone VARCHAR(255) DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_292F436DEA67784A (agenda_id), INDEX IDX_292F436DA76ED395 (user_id), INDEX IDX_292F436D7E3C61F9 (owner_id), INDEX IDX_292F436DB5224DF6 (delegation_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE delegation_type (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', code VARCHAR(255) NOT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE delegation_type_rights (delegation_type_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', rights_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_15597668B5224DF6 (delegation_type_id), INDEX IDX_15597668B196EE6E (rights_id), PRIMARY KEY(delegation_type_id, rights_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE personalized_delegation_rights (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', delegation_type_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', delegation_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', rights_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', created_by VARCHAR(255) DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, timezone VARCHAR(255) DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_5484E804B5224DF6 (delegation_type_id), INDEX IDX_5484E80456CBBCF5 (delegation_id), INDEX IDX_5484E804B196EE6E (rights_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rights (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', code VARCHAR(255) NOT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role_globals (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', role VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role_globals_rights (role_globals_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', rights_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_5487B24E8FD0D5DB (role_globals_id), INDEX IDX_5487B24EB196EE6E (rights_id), PRIMARY KEY(role_globals_id, rights_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE delegation ADD CONSTRAINT FK_292F436DEA67784A FOREIGN KEY (agenda_id) REFERENCES agenda (id)');
        $this->addSql('ALTER TABLE delegation ADD CONSTRAINT FK_292F436DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE delegation ADD CONSTRAINT FK_292F436D7E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE delegation ADD CONSTRAINT FK_292F436DB5224DF6 FOREIGN KEY (delegation_type_id) REFERENCES delegation_type (id)');
        $this->addSql('ALTER TABLE delegation_type_rights ADD CONSTRAINT FK_15597668B5224DF6 FOREIGN KEY (delegation_type_id) REFERENCES delegation_type (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE delegation_type_rights ADD CONSTRAINT FK_15597668B196EE6E FOREIGN KEY (rights_id) REFERENCES rights (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE personalized_delegation_rights ADD CONSTRAINT FK_5484E804B5224DF6 FOREIGN KEY (delegation_type_id) REFERENCES delegation_type (id)');
        $this->addSql('ALTER TABLE personalized_delegation_rights ADD CONSTRAINT FK_5484E80456CBBCF5 FOREIGN KEY (delegation_id) REFERENCES delegation (id)');
        $this->addSql('ALTER TABLE personalized_delegation_rights ADD CONSTRAINT FK_5484E804B196EE6E FOREIGN KEY (rights_id) REFERENCES rights (id)');
        $this->addSql('ALTER TABLE role_globals_rights ADD CONSTRAINT FK_5487B24E8FD0D5DB FOREIGN KEY (role_globals_id) REFERENCES role_globals (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE role_globals_rights ADD CONSTRAINT FK_5487B24EB196EE6E FOREIGN KEY (rights_id) REFERENCES rights (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE personalized_delegation_rights DROP FOREIGN KEY FK_5484E80456CBBCF5');
        $this->addSql('ALTER TABLE delegation DROP FOREIGN KEY FK_292F436DB5224DF6');
        $this->addSql('ALTER TABLE delegation_type_rights DROP FOREIGN KEY FK_15597668B5224DF6');
        $this->addSql('ALTER TABLE personalized_delegation_rights DROP FOREIGN KEY FK_5484E804B5224DF6');
        $this->addSql('ALTER TABLE delegation_type_rights DROP FOREIGN KEY FK_15597668B196EE6E');
        $this->addSql('ALTER TABLE personalized_delegation_rights DROP FOREIGN KEY FK_5484E804B196EE6E');
        $this->addSql('ALTER TABLE role_globals_rights DROP FOREIGN KEY FK_5487B24EB196EE6E');
        $this->addSql('ALTER TABLE role_globals_rights DROP FOREIGN KEY FK_5487B24E8FD0D5DB');
        $this->addSql('DROP TABLE delegation');
        $this->addSql('DROP TABLE delegation_type');
        $this->addSql('DROP TABLE delegation_type_rights');
        $this->addSql('DROP TABLE personalized_delegation_rights');
        $this->addSql('DROP TABLE rights');
        $this->addSql('DROP TABLE role_globals');
        $this->addSql('DROP TABLE role_globals_rights');
    }
}
