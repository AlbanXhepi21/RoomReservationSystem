<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
<<<<<<<< HEAD:migrations/Version20220913084707.php
final class Version20220913084707 extends AbstractMigration
========
final class Version20220912122422 extends AbstractMigration
>>>>>>>> 216e8d757f6a384104966115452dc1ce5bace4fd:migrations/Version20220912122422.php
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
<<<<<<<< HEAD:migrations/Version20220913084707.php
        $this->addSql('ALTER TABLE building ADD admin_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE building ADD CONSTRAINT FK_E16F61D4642B8210 FOREIGN KEY (admin_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_E16F61D4642B8210 ON building (admin_id)');
========
        $this->addSql('ALTER TABLE user ADD agreed_terms_at DATE NOT NULL');
>>>>>>>> 216e8d757f6a384104966115452dc1ce5bace4fd:migrations/Version20220912122422.php
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
<<<<<<<< HEAD:migrations/Version20220913084707.php
        $this->addSql('ALTER TABLE building DROP FOREIGN KEY FK_E16F61D4642B8210');
        $this->addSql('DROP INDEX IDX_E16F61D4642B8210 ON building');
        $this->addSql('ALTER TABLE building DROP admin_id');
========
        $this->addSql('ALTER TABLE `user` DROP agreed_terms_at');
>>>>>>>> 216e8d757f6a384104966115452dc1ce5bace4fd:migrations/Version20220912122422.php
    }
}
