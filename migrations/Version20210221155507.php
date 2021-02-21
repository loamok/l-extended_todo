<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210221155507 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE freebusy_category (freebusy_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', category_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_4D60D6B3D9267A93 (freebusy_id), INDEX IDX_4D60D6B312469DE2 (category_id), PRIMARY KEY(freebusy_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE freebusy_category ADD CONSTRAINT FK_4D60D6B3D9267A93 FOREIGN KEY (freebusy_id) REFERENCES freebusy (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE freebusy_category ADD CONSTRAINT FK_4D60D6B312469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE freebusy_category');
    }
}
