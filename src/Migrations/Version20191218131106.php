<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191218131106 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE recover_hash ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE recover_hash ADD CONSTRAINT FK_94C9A059A76ED395 FOREIGN KEY (user_id) REFERENCES usuario (id)');
        $this->addSql('CREATE INDEX IDX_94C9A059A76ED395 ON recover_hash (user_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE recover_hash DROP FOREIGN KEY FK_94C9A059A76ED395');
        $this->addSql('DROP INDEX IDX_94C9A059A76ED395 ON recover_hash');
        $this->addSql('ALTER TABLE recover_hash DROP user_id');
    }
}
