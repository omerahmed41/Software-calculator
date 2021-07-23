<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210722123924 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
//        $this->addSql('CREATE TABLE equation_log (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
//        $this->addSql('CREATE TABLE operations_log (id INT AUTO_INCREMENT NOT NULL, equation_id INT DEFAULT NULL, operation_type VARCHAR(32) NOT NULL, INDEX IDX_E915EEAB9BBD95AB (equation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
//        $this->addSql('ALTER TABLE operations_log ADD CONSTRAINT FK_E915EEAB9BBD95AB FOREIGN KEY (equation_id) REFERENCES equation_log (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE operations_log DROP FOREIGN KEY FK_E915EEAB9BBD95AB');
        $this->addSql('DROP TABLE equation_log');
        $this->addSql('DROP TABLE operations_log');
    }
}
