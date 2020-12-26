<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201226152453 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ag_type (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', code VARCHAR(255) NOT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE agenda (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', tz_id INT NOT NULL, type_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(255) NOT NULL, created_by VARCHAR(255) DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, timezone VARCHAR(255) DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_2CEDC87757F2EDC8 (tz_id), INDEX IDX_2CEDC877C54C8C93 (type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE timezone (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, label VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE agenda ADD CONSTRAINT FK_2CEDC87757F2EDC8 FOREIGN KEY (tz_id) REFERENCES timezone (id)');
        $this->addSql('ALTER TABLE agenda ADD CONSTRAINT FK_2CEDC877C54C8C93 FOREIGN KEY (type_id) REFERENCES ag_type (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE agenda DROP FOREIGN KEY FK_2CEDC877C54C8C93');
        $this->addSql('ALTER TABLE agenda DROP FOREIGN KEY FK_2CEDC87757F2EDC8');
        $this->addSql('DROP TABLE ag_type');
        $this->addSql('DROP TABLE agenda');
        $this->addSql('DROP TABLE timezone');
    }
}
