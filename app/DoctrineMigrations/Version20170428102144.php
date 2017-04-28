<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170428102144 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE promotion (id INT AUTO_INCREMENT NOT NULL, percent INT NOT NULL, startDate DATETIME NOT NULL, endDate DATETIME NOT NULL, isGeneral TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE promotion2product (promotion_id INT NOT NULL, product_id INT NOT NULL, INDEX IDX_C0D947FD139DF194 (promotion_id), INDEX IDX_C0D947FD4584665A (product_id), PRIMARY KEY(promotion_id, product_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE promotion2product_category (promotion_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_43DEEBBA139DF194 (promotion_id), INDEX IDX_43DEEBBA12469DE2 (category_id), PRIMARY KEY(promotion_id, category_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE promotion2user (promotion_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_5ACBED2A139DF194 (promotion_id), INDEX IDX_5ACBED2AA76ED395 (user_id), PRIMARY KEY(promotion_id, user_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE promotion2product ADD CONSTRAINT FK_C0D947FD139DF194 FOREIGN KEY (promotion_id) REFERENCES promotion (id)');
        $this->addSql('ALTER TABLE promotion2product ADD CONSTRAINT FK_C0D947FD4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE promotion2product_category ADD CONSTRAINT FK_43DEEBBA139DF194 FOREIGN KEY (promotion_id) REFERENCES promotion (id)');
        $this->addSql('ALTER TABLE promotion2product_category ADD CONSTRAINT FK_43DEEBBA12469DE2 FOREIGN KEY (category_id) REFERENCES product_category (id)');
        $this->addSql('ALTER TABLE promotion2user ADD CONSTRAINT FK_5ACBED2A139DF194 FOREIGN KEY (promotion_id) REFERENCES promotion (id)');
        $this->addSql('ALTER TABLE promotion2user ADD CONSTRAINT FK_5ACBED2AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE promotion2product DROP FOREIGN KEY FK_C0D947FD139DF194');
        $this->addSql('ALTER TABLE promotion2product_category DROP FOREIGN KEY FK_43DEEBBA139DF194');
        $this->addSql('ALTER TABLE promotion2user DROP FOREIGN KEY FK_5ACBED2A139DF194');
        $this->addSql('DROP TABLE promotion');
        $this->addSql('DROP TABLE promotion2product');
        $this->addSql('DROP TABLE promotion2product_category');
        $this->addSql('DROP TABLE promotion2user');
    }
}
