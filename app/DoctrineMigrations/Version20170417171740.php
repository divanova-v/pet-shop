<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170417171740 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE sale_offer (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, user_id INT DEFAULT NULL, description LONGTEXT NOT NULL, price NUMERIC(10, 2) NOT NULL, quantity INT NOT NULL, showOrder INT NOT NULL, createdOn DATETIME NOT NULL, updatedOn DATETIME NOT NULL, INDEX IDX_F90ADFBD4584665A (product_id), INDEX IDX_F90ADFBDA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user2_product (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, sale_offer_id INT DEFAULT NULL, user_id INT NOT NULL, saleOffer_id INT NOT NULL, quantity INT NOT NULL, INDEX IDX_EAA367234584665A (product_id), INDEX IDX_EAA367234DB29844 (sale_offer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sale_offer ADD CONSTRAINT FK_F90ADFBD4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE sale_offer ADD CONSTRAINT FK_F90ADFBDA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user2_product ADD CONSTRAINT FK_EAA367234584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE user2_product ADD CONSTRAINT FK_EAA367234DB29844 FOREIGN KEY (sale_offer_id) REFERENCES sale_offer (id)');
        $this->addSql('ALTER TABLE product DROP price, DROP description');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user2_product DROP FOREIGN KEY FK_EAA367234DB29844');
        $this->addSql('DROP TABLE sale_offer');
        $this->addSql('DROP TABLE user2_product');
        $this->addSql('ALTER TABLE product ADD price NUMERIC(2, 0) NOT NULL, ADD description LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci');
    }
}
