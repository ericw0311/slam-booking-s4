<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181207063413 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE booking_duplication (id INT AUTO_INCREMENT NOT NULL, origin_booking_id INT NOT NULL, new_booking_id INT NOT NULL, user_id INT NOT NULL, gap INT NOT NULL, ddate DATE NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_14BE610EBAEB462D (origin_booking_id), INDEX IDX_14BE610EDE0FFFAC (new_booking_id), INDEX IDX_14BE610EA76ED395 (user_id), UNIQUE INDEX uk_booking_duplication (origin_booking_id, ddate), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE booking_duplication ADD CONSTRAINT FK_14BE610EBAEB462D FOREIGN KEY (origin_booking_id) REFERENCES booking (id)');
        $this->addSql('ALTER TABLE booking_duplication ADD CONSTRAINT FK_14BE610EDE0FFFAC FOREIGN KEY (new_booking_id) REFERENCES booking (id)');
        $this->addSql('ALTER TABLE booking_duplication ADD CONSTRAINT FK_14BE610EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE booking_duplication');
    }
}
