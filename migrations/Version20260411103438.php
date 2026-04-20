<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260411103438 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE shop DROP FOREIGN KEY `FK_AC6A4CA255BB310E`');
        $this->addSql('DROP INDEX IDX_AC6A4CA255BB310E ON shop');
        $this->addSql('ALTER TABLE shop CHANGE productor_id producer_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE shop ADD CONSTRAINT FK_AC6A4CA289B658FE FOREIGN KEY (producer_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_AC6A4CA289B658FE ON shop (producer_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE shop DROP FOREIGN KEY FK_AC6A4CA289B658FE');
        $this->addSql('DROP INDEX IDX_AC6A4CA289B658FE ON shop');
        $this->addSql('ALTER TABLE shop CHANGE producer_id productor_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE shop ADD CONSTRAINT `FK_AC6A4CA255BB310E` FOREIGN KEY (productor_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_AC6A4CA255BB310E ON shop (productor_id)');
    }
}
