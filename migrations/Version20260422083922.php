<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260422083922 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) DEFAULT NULL, slug VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, unit VARCHAR(255) DEFAULT NULL, price DOUBLE PRECISION DEFAULT NULL, updated_at DATETIME DEFAULT NULL, created_at DATETIME DEFAULT NULL, label VARCHAR(255) DEFAULT NULL, producer_id INT DEFAULT NULL, INDEX IDX_D34A04AD89B658FE (producer_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE product_sell_slot (product_id INT NOT NULL, sell_slot_id INT NOT NULL, INDEX IDX_DDA5BD3D4584665A (product_id), INDEX IDX_DDA5BD3D308116D7 (sell_slot_id), PRIMARY KEY (product_id, sell_slot_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD89B658FE FOREIGN KEY (producer_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE product_sell_slot ADD CONSTRAINT FK_DDA5BD3D4584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_sell_slot ADD CONSTRAINT FK_DDA5BD3D308116D7 FOREIGN KEY (sell_slot_id) REFERENCES sell_slot (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD89B658FE');
        $this->addSql('ALTER TABLE product_sell_slot DROP FOREIGN KEY FK_DDA5BD3D4584665A');
        $this->addSql('ALTER TABLE product_sell_slot DROP FOREIGN KEY FK_DDA5BD3D308116D7');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE product_sell_slot');
    }
}
