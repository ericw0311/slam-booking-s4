<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180824025904 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE planification_line (id INT AUTO_INCREMENT NOT NULL, planification_period_id INT NOT NULL, timetable_id INT NOT NULL, week_day VARCHAR(255) NOT NULL, oorder SMALLINT NOT NULL, active TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_D967D141DCFAF5EA (planification_period_id), INDEX IDX_D967D141CC306847 (timetable_id), UNIQUE INDEX uk_planification_line (planification_period_id, week_day), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE planification_resource (id INT AUTO_INCREMENT NOT NULL, planification_period_id INT NOT NULL, resource_id INT NOT NULL, oorder SMALLINT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_78E129F9DCFAF5EA (planification_period_id), INDEX IDX_78E129F989329D25 (resource_id), UNIQUE INDEX uk_planification_resource (planification_period_id, resource_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE planification_period (id INT AUTO_INCREMENT NOT NULL, planification_id INT NOT NULL, beginning_date DATE DEFAULT NULL, end_date DATE DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_53E4BB15E65142C2 (planification_id), UNIQUE INDEX uk_planification_period (planification_id, beginning_date), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE planification (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, file_id INT NOT NULL, type VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, internal TINYINT(1) NOT NULL, code VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_FFC02E1BA76ED395 (user_id), INDEX IDX_FFC02E1B93CB796C (file_id), UNIQUE INDEX uk_planification (file_id, type, name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE planification_line ADD CONSTRAINT FK_D967D141DCFAF5EA FOREIGN KEY (planification_period_id) REFERENCES planification_period (id)');
        $this->addSql('ALTER TABLE planification_line ADD CONSTRAINT FK_D967D141CC306847 FOREIGN KEY (timetable_id) REFERENCES timetable (id)');
        $this->addSql('ALTER TABLE planification_resource ADD CONSTRAINT FK_78E129F9DCFAF5EA FOREIGN KEY (planification_period_id) REFERENCES planification_period (id)');
        $this->addSql('ALTER TABLE planification_resource ADD CONSTRAINT FK_78E129F989329D25 FOREIGN KEY (resource_id) REFERENCES resource (id)');
        $this->addSql('ALTER TABLE planification_period ADD CONSTRAINT FK_53E4BB15E65142C2 FOREIGN KEY (planification_id) REFERENCES planification (id)');
        $this->addSql('ALTER TABLE planification ADD CONSTRAINT FK_FFC02E1BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE planification ADD CONSTRAINT FK_FFC02E1B93CB796C FOREIGN KEY (file_id) REFERENCES file (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE planification_line DROP FOREIGN KEY FK_D967D141DCFAF5EA');
        $this->addSql('ALTER TABLE planification_resource DROP FOREIGN KEY FK_78E129F9DCFAF5EA');
        $this->addSql('ALTER TABLE planification_period DROP FOREIGN KEY FK_53E4BB15E65142C2');
        $this->addSql('DROP TABLE planification_line');
        $this->addSql('DROP TABLE planification_resource');
        $this->addSql('DROP TABLE planification_period');
        $this->addSql('DROP TABLE planification');
    }
}
