<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180905042315 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE booking_label (id INT AUTO_INCREMENT NOT NULL, booking_id INT NOT NULL, label_id INT NOT NULL, user_id INT NOT NULL, oorder SMALLINT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_9FDBAD5D3301C60 (booking_id), INDEX IDX_9FDBAD5D33B92F39 (label_id), INDEX IDX_9FDBAD5DA76ED395 (user_id), UNIQUE INDEX uk_booking_label (booking_id, label_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE booking_line (id INT AUTO_INCREMENT NOT NULL, booking_id INT NOT NULL, planification_id INT NOT NULL, planification_period_id INT NOT NULL, planification_line_id INT NOT NULL, resource_id INT NOT NULL, timetable_id INT NOT NULL, timetable_line_id INT NOT NULL, user_id INT NOT NULL, ddate DATE NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_C98596B83301C60 (booking_id), INDEX IDX_C98596B8E65142C2 (planification_id), INDEX IDX_C98596B8DCFAF5EA (planification_period_id), INDEX IDX_C98596B85CEC22BB (planification_line_id), INDEX IDX_C98596B889329D25 (resource_id), INDEX IDX_C98596B8CC306847 (timetable_id), INDEX IDX_C98596B8CC1B3F3C (timetable_line_id), INDEX IDX_C98596B8A76ED395 (user_id), UNIQUE INDEX uk_booking_line (resource_id, ddate, timetable_id, timetable_line_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE note (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, note LONGTEXT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_CFBDFA14A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE booking_user (id INT AUTO_INCREMENT NOT NULL, booking_id INT NOT NULL, user_file_id INT NOT NULL, user_id INT NOT NULL, oorder SMALLINT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_9502F4073301C60 (booking_id), INDEX IDX_9502F407CBC66766 (user_file_id), INDEX IDX_9502F407A76ED395 (user_id), UNIQUE INDEX uk_booking_user (booking_id, user_file_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE booking (id INT AUTO_INCREMENT NOT NULL, planification_id INT NOT NULL, resource_id INT NOT NULL, user_id INT NOT NULL, file_id INT NOT NULL, form_note_id INT DEFAULT NULL, note LONGTEXT DEFAULT NULL, beginning_date DATETIME NOT NULL, end_date DATETIME NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_E00CEDDEE65142C2 (planification_id), INDEX IDX_E00CEDDE89329D25 (resource_id), INDEX IDX_E00CEDDEA76ED395 (user_id), INDEX IDX_E00CEDDE93CB796C (file_id), UNIQUE INDEX UNIQ_E00CEDDE1781686E (form_note_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE booking_label ADD CONSTRAINT FK_9FDBAD5D3301C60 FOREIGN KEY (booking_id) REFERENCES booking (id)');
        $this->addSql('ALTER TABLE booking_label ADD CONSTRAINT FK_9FDBAD5D33B92F39 FOREIGN KEY (label_id) REFERENCES label (id)');
        $this->addSql('ALTER TABLE booking_label ADD CONSTRAINT FK_9FDBAD5DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE booking_line ADD CONSTRAINT FK_C98596B83301C60 FOREIGN KEY (booking_id) REFERENCES booking (id)');
        $this->addSql('ALTER TABLE booking_line ADD CONSTRAINT FK_C98596B8E65142C2 FOREIGN KEY (planification_id) REFERENCES planification (id)');
        $this->addSql('ALTER TABLE booking_line ADD CONSTRAINT FK_C98596B8DCFAF5EA FOREIGN KEY (planification_period_id) REFERENCES planification_period (id)');
        $this->addSql('ALTER TABLE booking_line ADD CONSTRAINT FK_C98596B85CEC22BB FOREIGN KEY (planification_line_id) REFERENCES planification_line (id)');
        $this->addSql('ALTER TABLE booking_line ADD CONSTRAINT FK_C98596B889329D25 FOREIGN KEY (resource_id) REFERENCES resource (id)');
        $this->addSql('ALTER TABLE booking_line ADD CONSTRAINT FK_C98596B8CC306847 FOREIGN KEY (timetable_id) REFERENCES timetable (id)');
        $this->addSql('ALTER TABLE booking_line ADD CONSTRAINT FK_C98596B8CC1B3F3C FOREIGN KEY (timetable_line_id) REFERENCES timetable_line (id)');
        $this->addSql('ALTER TABLE booking_line ADD CONSTRAINT FK_C98596B8A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA14A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE booking_user ADD CONSTRAINT FK_9502F4073301C60 FOREIGN KEY (booking_id) REFERENCES booking (id)');
        $this->addSql('ALTER TABLE booking_user ADD CONSTRAINT FK_9502F407CBC66766 FOREIGN KEY (user_file_id) REFERENCES user_file (id)');
        $this->addSql('ALTER TABLE booking_user ADD CONSTRAINT FK_9502F407A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDEE65142C2 FOREIGN KEY (planification_id) REFERENCES planification (id)');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDE89329D25 FOREIGN KEY (resource_id) REFERENCES resource (id)');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDEA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDE93CB796C FOREIGN KEY (file_id) REFERENCES file (id)');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDE1781686E FOREIGN KEY (form_note_id) REFERENCES note (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE booking DROP FOREIGN KEY FK_E00CEDDE1781686E');
        $this->addSql('ALTER TABLE booking_label DROP FOREIGN KEY FK_9FDBAD5D3301C60');
        $this->addSql('ALTER TABLE booking_line DROP FOREIGN KEY FK_C98596B83301C60');
        $this->addSql('ALTER TABLE booking_user DROP FOREIGN KEY FK_9502F4073301C60');
        $this->addSql('DROP TABLE booking_label');
        $this->addSql('DROP TABLE booking_line');
        $this->addSql('DROP TABLE note');
        $this->addSql('DROP TABLE booking_user');
        $this->addSql('DROP TABLE booking');
    }
}
