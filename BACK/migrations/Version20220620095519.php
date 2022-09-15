<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220620095519 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE documents (id INT AUTO_INCREMENT NOT NULL, id_folder_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, size VARCHAR(255) NOT NULL, path VARCHAR(255) NOT NULL, to_Index TINYINT(1) NOT NULL, Indices IDX_A2B0728896190565 (id_folder_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE folder (id INT AUTO_INCREMENT NOT NULL, id_organization_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, Indices IDX_ECA209CDDB966056 (id_organization_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE history (id INT AUTO_INCREMENT NOT NULL, id_documents_id INT DEFAULT NULL, id_users_id INT DEFAULT NULL, action VARCHAR(255) NOT NULL, date DATE NOT NULL, Indices IDX_27BA704B7CCE5220 (id_documents_id), Indices IDX_27BA704B376858A8 (id_users_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE indices (id INT AUTO_INCREMENT NOT NULL, id_rules_id INT DEFAULT NULL, id_documents_id INT DEFAULT NULL, value VARCHAR(255) NOT NULL, Indices IDX_80736701ABB27ED1 (id_rules_id), Indices IDX_807367017CCE5220 (id_documents_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE organizations (id INT AUTO_INCREMENT NOT NULL, owner_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, Indices IDX_427C1C7F7E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rules (id INT AUTO_INCREMENT NOT NULL, id_folder_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, mandatory TINYINT(1) NOT NULL, Indices IDX_899A993C96190565 (id_folder_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_in_organizations (id INT AUTO_INCREMENT NOT NULL, id_user_id INT DEFAULT NULL, id_organization_id INT DEFAULT NULL, roles VARCHAR(255) NOT NULL, Indices IDX_E64EF2D179F37AE5 (id_user_id), Indices IDX_E64EF2D1DB966056 (id_organization_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE documents ADD CONSTRAINT FK_A2B0728896190565 FOREIGN KEY (id_folder_id) REFERENCES folder (id)');
        $this->addSql('ALTER TABLE folder ADD CONSTRAINT FK_ECA209CDDB966056 FOREIGN KEY (id_organization_id) REFERENCES organizations (id)');
        $this->addSql('ALTER TABLE history ADD CONSTRAINT FK_27BA704B7CCE5220 FOREIGN KEY (id_documents_id) REFERENCES documents (id)');
        $this->addSql('ALTER TABLE history ADD CONSTRAINT FK_27BA704B376858A8 FOREIGN KEY (id_users_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE indices ADD CONSTRAINT FK_80736701ABB27ED1 FOREIGN KEY (id_rules_id) REFERENCES rules (id)');
        $this->addSql('ALTER TABLE indices ADD CONSTRAINT FK_807367017CCE5220 FOREIGN KEY (id_documents_id) REFERENCES documents (id)');
        $this->addSql('ALTER TABLE organizations ADD CONSTRAINT FK_427C1C7F7E3C61F9 FOREIGN KEY (owner_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE rules ADD CONSTRAINT FK_899A993C96190565 FOREIGN KEY (id_folder_id) REFERENCES folder (id)');
        $this->addSql('ALTER TABLE user_in_organizations ADD CONSTRAINT FK_E64EF2D179F37AE5 FOREIGN KEY (id_user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE user_in_organizations ADD CONSTRAINT FK_E64EF2D1DB966056 FOREIGN KEY (id_organization_id) REFERENCES organizations (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE history DROP FOREIGN KEY FK_27BA704B7CCE5220');
        $this->addSql('ALTER TABLE indices DROP FOREIGN KEY FK_807367017CCE5220');
        $this->addSql('ALTER TABLE documents DROP FOREIGN KEY FK_A2B0728896190565');
        $this->addSql('ALTER TABLE rules DROP FOREIGN KEY FK_899A993C96190565');
        $this->addSql('ALTER TABLE folder DROP FOREIGN KEY FK_ECA209CDDB966056');
        $this->addSql('ALTER TABLE user_in_organizations DROP FOREIGN KEY FK_E64EF2D1DB966056');
        $this->addSql('ALTER TABLE indices DROP FOREIGN KEY FK_80736701ABB27ED1');
        $this->addSql('ALTER TABLE history DROP FOREIGN KEY FK_27BA704B376858A8');
        $this->addSql('ALTER TABLE organizations DROP FOREIGN KEY FK_427C1C7F7E3C61F9');
        $this->addSql('ALTER TABLE user_in_organizations DROP FOREIGN KEY FK_E64EF2D179F37AE5');
        $this->addSql('DROP TABLE documents');
        $this->addSql('DROP TABLE folder');
        $this->addSql('DROP TABLE history');
        $this->addSql('DROP TABLE indices');
        $this->addSql('DROP TABLE organizations');
        $this->addSql('DROP TABLE rules');
        $this->addSql('DROP TABLE user_in_organizations');
        $this->addSql('DROP TABLE users');
    }
}
