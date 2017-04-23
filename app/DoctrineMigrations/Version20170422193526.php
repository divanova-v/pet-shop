<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170422193526 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE sale_offer DROP FOREIGN KEY FK_F90ADFBD4584665A');
        $this->addSql('DROP INDEX IDX_F90ADFBD4584665A ON sale_offer');
        $this->addSql('ALTER TABLE sale_offer ADD sale_offer_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sale_offer ADD CONSTRAINT FK_F90ADFBD4DB29844 FOREIGN KEY (sale_offer_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_F90ADFBD4DB29844 ON sale_offer (sale_offer_id)');
        $this->addSql('ALTER TABLE user2_product DROP FOREIGN KEY FK_EAA367234DB29844');
        $this->addSql('ALTER TABLE user2_product CHANGE sale_offer_id sale_offer_id INT NOT NULL');
        $this->addSql('ALTER TABLE user2_product ADD CONSTRAINT FK_EAA367234DB29844 FOREIGN KEY (sale_offer_id) REFERENCES sale_offer (id)');


    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE sale_offer DROP FOREIGN KEY FK_F90ADFBD4DB29844');
        $this->addSql('DROP INDEX IDX_F90ADFBD4DB29844 ON sale_offer');
        $this->addSql('ALTER TABLE sale_offer DROP sale_offer_id');
        $this->addSql('ALTER TABLE sale_offer ADD CONSTRAINT FK_F90ADFBD4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('CREATE INDEX IDX_F90ADFBD4584665A ON sale_offer (product_id)');
        $this->addSql('ALTER TABLE user2_product DROP FOREIGN KEY FK_EAA367234DB29844');
        $this->addSql('ALTER TABLE user2_product CHANGE sale_offer_id sale_offer_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user2_product ADD CONSTRAINT FK_EAA367234DB29844 FOREIGN KEY (sale_offer_id) REFERENCES sale_offer (id)');

    }
}
