<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260929174819 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Stores the Ekvall dimension circle order.';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE dimension ADD position INT NOT NULL');
        $this->addSql(<<<'SQL'
            UPDATE dimension
            SET position = CASE name
                WHEN 'Vrijheid' THEN 1
                WHEN 'Ideesupport' THEN 2
                WHEN 'Vertrouwen en openheid' THEN 3
                WHEN 'Dynamiek en levendigheid' THEN 4
                WHEN 'Speelsheid en humor' THEN 5
                WHEN 'Dialoog' THEN 6
                WHEN 'Risico nemen' THEN 7
                WHEN 'Tijd voor ideeën' THEN 8
                WHEN 'Conflict' THEN 9
                WHEN 'Uitdaging' THEN 10
                ELSE 0
            END
            SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE dimension DROP position');
    }
}
