<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220504132959 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE transaction_table (id INT AUTO_INCREMENT NOT NULL, network_id INT DEFAULT NULL, user_id INT DEFAULT NULL, pakage_id INT DEFAULT NULL, cash VARCHAR(255) DEFAULT NULL, direct INT DEFAULT NULL, withdrawal_to_wallet INT DEFAULT NULL, withdrawal INT DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, application_withdrawal INT DEFAULT NULL, application_withdrawal_status INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE transaction_table');
        $this->addSql('ALTER TABLE fast_consultation CHANGE name name VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE phone phone VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE email email VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE question question VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE list_referral_networks CHANGE name name VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE owner_name owner_name VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE unique_code unique_code VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE network_code network_code VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE client_code client_code VARCHAR(100) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE pakege CHANGE name name VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE referral_networks_id referral_networks_id VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE client_code client_code VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE activation activation VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE unique_code unique_code VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE referral_link referral_link VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE personal_data CHANGE name name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE surname surname VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE phone phone VARCHAR(25) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE state state VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE region region VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE city city VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE street street VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE frame frame VARCHAR(25) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE block block VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE client_code client_code VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE referral_network CHANGE name name VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE user_status user_status VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE network_code network_code VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE member_code member_code VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE my_team my_team VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE referral_to_email CHANGE referral_link referral_link VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE email_to_client email_to_client VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE reset_password_request CHANGE selector selector VARCHAR(20) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE hashed_token hashed_token VARCHAR(100) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE saving_mail CHANGE from_mail from_mail VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE to_mail to_mail VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE status status VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE text text VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE category category VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE setting_options CHANGE name_multi_pakage name_multi_pakage VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE table_pakage CHANGE name name VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE price_pakage price_pakage VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE description description VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE user CHANGE email email VARCHAR(180) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE password password VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE username username VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE role role VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE referral_link referral_link VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE pesonal_code pesonal_code VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE secret_code secret_code VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE locale locale VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
