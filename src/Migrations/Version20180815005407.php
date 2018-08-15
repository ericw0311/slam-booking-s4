<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180815005407 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE resource (id INT AUTO_INCREMENT NOT NULL, classification_id INT NOT NULL, user_id INT NOT NULL, file_id INT NOT NULL, internal TINYINT(1) NOT NULL, type VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_BC91F4162A86559F (classification_id), INDEX IDX_BC91F416A76ED395 (user_id), INDEX IDX_BC91F41693CB796C (file_id), UNIQUE INDEX uk_resource (file_id, type, name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE resource_classification (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, file_id INT NOT NULL, internal TINYINT(1) NOT NULL, type VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, active TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_37255992A76ED395 (user_id), INDEX IDX_3725599293CB796C (file_id), UNIQUE INDEX uk_resource_classification (file_id, internal, type, name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE resource ADD CONSTRAINT FK_BC91F4162A86559F FOREIGN KEY (classification_id) REFERENCES resource_classification (id)');
        $this->addSql('ALTER TABLE resource ADD CONSTRAINT FK_BC91F416A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE resource ADD CONSTRAINT FK_BC91F41693CB796C FOREIGN KEY (file_id) REFERENCES file (id)');
        $this->addSql('ALTER TABLE resource_classification ADD CONSTRAINT FK_37255992A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE resource_classification ADD CONSTRAINT FK_3725599293CB796C FOREIGN KEY (file_id) REFERENCES file (id)');
        $this->addSql('ALTER TABLE user_file ADD resource_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user_file ADD CONSTRAINT FK_F61E7AD989329D25 FOREIGN KEY (resource_id) REFERENCES resource (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F61E7AD989329D25 ON user_file (resource_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user_file DROP FOREIGN KEY FK_F61E7AD989329D25');
        $this->addSql('ALTER TABLE resource DROP FOREIGN KEY FK_BC91F4162A86559F');
        $this->addSql('DROP TABLE resource');
        $this->addSql('DROP TABLE resource_classification');
        $this->addSql('DROP INDEX UNIQ_F61E7AD989329D25 ON user_file');
        $this->addSql('ALTER TABLE user_file DROP resource_id');
    }
}
