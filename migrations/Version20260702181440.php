<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260702181440 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE consumer (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, phone_number VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE customer_order (id INT AUTO_INCREMENT NOT NULL, total DOUBLE PRECISION NOT NULL, created_at DATETIME NOT NULL, consumer_id INT NOT NULL, UNIQUE INDEX UNIQ_3B1CE6A337FDBD6D (consumer_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE customer_order_item (id INT AUTO_INCREMENT NOT NULL, quantity INT NOT NULL, unit_price_at_purchase DOUBLE PRECISION NOT NULL, product_id INT NOT NULL, customer_order_id INT NOT NULL, INDEX IDX_AF231B8B4584665A (product_id), INDEX IDX_AF231B8BA15A2E17 (customer_order_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE customer_order ADD CONSTRAINT FK_3B1CE6A337FDBD6D FOREIGN KEY (consumer_id) REFERENCES consumer (id)');
        $this->addSql('ALTER TABLE customer_order_item ADD CONSTRAINT FK_AF231B8B4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE customer_order_item ADD CONSTRAINT FK_AF231B8BA15A2E17 FOREIGN KEY (customer_order_id) REFERENCES customer_order (id)');
        $this->addSql('ALTER TABLE item_cart DROP FOREIGN KEY `FK_C393CC4C4584665A`');
        $this->addSql('DROP TABLE item_cart');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE item_cart (id INT AUTO_INCREMENT NOT NULL, quantity INT DEFAULT NULL, total DOUBLE PRECISION DEFAULT NULL, unit_price DOUBLE PRECISION DEFAULT NULL, product_id INT DEFAULT NULL, INDEX IDX_C393CC4C4584665A (product_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE item_cart ADD CONSTRAINT `FK_C393CC4C4584665A` FOREIGN KEY (product_id) REFERENCES product (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE customer_order DROP FOREIGN KEY FK_3B1CE6A337FDBD6D');
        $this->addSql('ALTER TABLE customer_order_item DROP FOREIGN KEY FK_AF231B8B4584665A');
        $this->addSql('ALTER TABLE customer_order_item DROP FOREIGN KEY FK_AF231B8BA15A2E17');
        $this->addSql('DROP TABLE consumer');
        $this->addSql('DROP TABLE customer_order');
        $this->addSql('DROP TABLE customer_order_item');
    }
}
