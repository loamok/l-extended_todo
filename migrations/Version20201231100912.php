<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201231100912 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE rel_type (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', code VARCHAR(255) NOT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE related (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', type_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', agenda_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', journal_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', todo_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', event_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', freebusy_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', created_by VARCHAR(255) DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, timezone VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_60577090C54C8C93 (type_id), INDEX IDX_60577090EA67784A (agenda_id), INDEX IDX_60577090478E8802 (journal_id), INDEX IDX_60577090EA1EBC33 (todo_id), INDEX IDX_6057709071F7E88B (event_id), INDEX IDX_60577090D9267A93 (freebusy_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE related ADD CONSTRAINT FK_60577090C54C8C93 FOREIGN KEY (type_id) REFERENCES rel_type (id)');
        $this->addSql('ALTER TABLE related ADD CONSTRAINT FK_60577090EA67784A FOREIGN KEY (agenda_id) REFERENCES agenda (id)');
        $this->addSql('ALTER TABLE related ADD CONSTRAINT FK_60577090478E8802 FOREIGN KEY (journal_id) REFERENCES journal (id)');
        $this->addSql('ALTER TABLE related ADD CONSTRAINT FK_60577090EA1EBC33 FOREIGN KEY (todo_id) REFERENCES todo (id)');
        $this->addSql('ALTER TABLE related ADD CONSTRAINT FK_6057709071F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE related ADD CONSTRAINT FK_60577090D9267A93 FOREIGN KEY (freebusy_id) REFERENCES freebusy (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE related DROP FOREIGN KEY FK_60577090C54C8C93');
        $this->addSql('DROP TABLE rel_type');
        $this->addSql('DROP TABLE related');
    }
}
