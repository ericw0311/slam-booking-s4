<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180831201507 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE planification_resource ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE planification_resource ADD CONSTRAINT FK_78E129F9A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_78E129F9A76ED395 ON planification_resource (user_id)');
        $this->addSql('ALTER TABLE planification_period ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE planification_period ADD CONSTRAINT FK_53E4BB15A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_53E4BB15A76ED395 ON planification_period (user_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE planification_period DROP FOREIGN KEY FK_53E4BB15A76ED395');
        $this->addSql('DROP INDEX IDX_53E4BB15A76ED395 ON planification_period');
        $this->addSql('ALTER TABLE planification_period DROP user_id');
        $this->addSql('ALTER TABLE planification_resource DROP FOREIGN KEY FK_78E129F9A76ED395');
        $this->addSql('DROP INDEX IDX_78E129F9A76ED395 ON planification_resource');
        $this->addSql('ALTER TABLE planification_resource DROP user_id');
    }
}
