<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260929173714 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creates the initial survey schema for MySQL.';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE answer (id INT AUTO_INCREMENT NOT NULL, value SMALLINT NOT NULL, question_id INT NOT NULL, submission_id INT NOT NULL, UNIQUE INDEX uniq_answer_submission_question (submission_id, question_id), INDEX IDX_DADD4A251E27F6BF (question_id), INDEX IDX_DADD4A25E1FD4933 (submission_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE dimension (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE question (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, dimension_id INT NOT NULL, INDEX IDX_B6F7494E277428AD (dimension_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE submission (id INT AUTO_INCREMENT NOT NULL, created_at DATETIME NOT NULL, team_id INT NOT NULL, dimension_id INT NOT NULL, INDEX IDX_DB055AF3296CD8AE (team_id), INDEX IDX_DB055AF3277428AD (dimension_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE team (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, code VARCHAR(15) NOT NULL, category_id INT NOT NULL, UNIQUE INDEX uniq_team_code (code), INDEX IDX_C4E0A61F12469DE2 (category_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE answer ADD CONSTRAINT FK_DADD4A251E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE answer ADD CONSTRAINT FK_DADD4A25E1FD4933 FOREIGN KEY (submission_id) REFERENCES submission (id)');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494E277428AD FOREIGN KEY (dimension_id) REFERENCES dimension (id)');
        $this->addSql('ALTER TABLE submission ADD CONSTRAINT FK_DB055AF3296CD8AE FOREIGN KEY (team_id) REFERENCES team (id)');
        $this->addSql('ALTER TABLE submission ADD CONSTRAINT FK_DB055AF3277428AD FOREIGN KEY (dimension_id) REFERENCES dimension (id)');
        $this->addSql('ALTER TABLE team ADD CONSTRAINT FK_C4E0A61F12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE answer DROP FOREIGN KEY FK_DADD4A251E27F6BF');
        $this->addSql('ALTER TABLE answer DROP FOREIGN KEY FK_DADD4A25E1FD4933');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494E277428AD');
        $this->addSql('ALTER TABLE submission DROP FOREIGN KEY FK_DB055AF3296CD8AE');
        $this->addSql('ALTER TABLE submission DROP FOREIGN KEY FK_DB055AF3277428AD');
        $this->addSql('ALTER TABLE team DROP FOREIGN KEY FK_C4E0A61F12469DE2');
        $this->addSql('DROP TABLE answer');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE dimension');
        $this->addSql('DROP TABLE question');
        $this->addSql('DROP TABLE submission');
        $this->addSql('DROP TABLE team');
    }
}
