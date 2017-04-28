<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170428114639 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE promotion2sale_offer (promotion_id INT NOT NULL, offer_id INT NOT NULL, INDEX IDX_27E42F26139DF194 (promotion_id), INDEX IDX_27E42F2653C674EE (offer_id), PRIMARY KEY(promotion_id, offer_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE promotion2sale_offer ADD CONSTRAINT FK_27E42F26139DF194 FOREIGN KEY (promotion_id) REFERENCES promotion (id)');
        $this->addSql('ALTER TABLE promotion2sale_offer ADD CONSTRAINT FK_27E42F2653C674EE FOREIGN KEY (offer_id) REFERENCES sale_offer (id)');
        $this->addSql('ALTER TABLE promotion2product DROP FOREIGN KEY FK_C0D947FD139DF194');
        $this->addSql('ALTER TABLE promotion2product DROP FOREIGN KEY FK_C0D947FD4584665A');
        $this->addSql('DROP TABLE promotion2product');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE promotion2product (promotion_id INT NOT NULL, product_id INT NOT NULL, INDEX IDX_C0D947FD139DF194 (promotion_id), INDEX IDX_C0D947FD4584665A (product_id), PRIMARY KEY(promotion_id, product_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE promotion2product ADD CONSTRAINT FK_C0D947FD139DF194 FOREIGN KEY (promotion_id) REFERENCES promotion (id)');
        $this->addSql('ALTER TABLE promotion2product ADD CONSTRAINT FK_C0D947FD4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE promotion2sale_offer DROP FOREIGN KEY FK_27E42F26139DF194');
        $this->addSql('ALTER TABLE promotion2sale_offer DROP FOREIGN KEY FK_27E42F2653C674EE');
        $this->addSql('DROP TABLE promotion2sale_offer');
    }
}
