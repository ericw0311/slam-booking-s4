<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180808020354 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE timetable (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, file_id INT NOT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, INDEX IDX_6B1F670A76ED395 (user_id), INDEX IDX_6B1F67093CB796C (file_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE timetable_line (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, timetable_id INT NOT NULL, type VARCHAR(255) NOT NULL, beginning_time TIME NOT NULL, end_time TIME NOT NULL, INDEX IDX_44285EF8A76ED395 (user_id), INDEX IDX_44285EF8CC306847 (timetable_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE timetable ADD CONSTRAINT FK_6B1F670A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE timetable ADD CONSTRAINT FK_6B1F67093CB796C FOREIGN KEY (file_id) REFERENCES file (id)');
        $this->addSql('ALTER TABLE timetable_line ADD CONSTRAINT FK_44285EF8A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE timetable_line ADD CONSTRAINT FK_44285EF8CC306847 FOREIGN KEY (timetable_id) REFERENCES timetable (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE timetable_line DROP FOREIGN KEY FK_44285EF8CC306847');
        $this->addSql('DROP TABLE timetable');
        $this->addSql('DROP TABLE timetable_line');
    }
}
