<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180725210631 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user_parameter (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, parameter_group VARCHAR(255) NOT NULL, parameter VARCHAR(255) NOT NULL, parameter_type VARCHAR(255) NOT NULL, integer_value INT DEFAULT NULL, string_value VARCHAR(255) DEFAULT NULL, boolean_value TINYINT(1) NOT NULL, INDEX IDX_2A771CF4A76ED395 (user_id), UNIQUE INDEX uk_user_parameter (user_id, parameter_group, parameter), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_parameter ADD CONSTRAINT FK_2A771CF4A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE user_parameter');
    }
}
