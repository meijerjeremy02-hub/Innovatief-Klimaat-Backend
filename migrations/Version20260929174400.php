<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260929174400 extends AbstractMigration
{
    private const DIMENSIONS = [
        'Vrijheid' => [
            'Ik krijg ruimte om mijn werk uit te voeren op een manier die bij mij past.',
            'Ik word gestimuleerd om initiatief te nemen en nieuwe ideeën uit te proberen.',
            'Ik ervaar dat regels en procedures mijn werk ondersteunen zonder onnodige belemmeringen op te leggen.',
            'Ik krijg de ruimte om bestaande werkwijzen kritisch te bekijken en alternatieven te bedenken.',
            'Ik heb voldoende invloed op de planning en organisatie van mijn werkzaamheden.',
        ],
        'Ideesupport' => [
            'Mijn nieuwe ideeën krijgen een eerlijke kans om besproken te worden.',
            'Ik luister actief naar voorstellen van collega’s.',
            'Ik reageer nieuwsgierig en constructief op nieuwe initiatieven.',
            'Ik zie dat ideeën niet alleen worden besproken, maar ook worden opgepakt en verder gebracht.',
            'Ik word door mijn leidinggevende gestimuleerd om met nieuwe ideeën te komen.',
        ],
        'Vertrouwen en openheid' => [
            'Ik voel mij veilig om mijn mening te geven, ook als die afwijkt van die van anderen.',
            'Ik ervaar dat collega’s en mijn leidinggevende mijn bijdrage serieus nemen.',
            'Ik ervaar dat fouten en mislukkingen worden gebruikt om van te leren en niet om iemand af te rekenen.',
            'Ik geef en ontvang feedback op een respectvolle en constructieve manier.',
            'Ik kan zorgen, twijfels en knelpunten binnen ons team bespreekbaar maken voordat ze problemen worden.',
        ],
        'Dynamiek en levendigheid' => [
            'Ik zoek actief naar kansen om mijn werk te verbeteren.',
            'Ik neem initiatief om ideeën en plannen in beweging te krijgen.',
            'Ik sta open voor nieuwe ontwikkelingen en speel hier actief op in.',
            'Ik pas mijn werkwijze tijdig aan wanneer omstandigheden veranderen.',
            'Ik ervaar binnen ons team een energieke en actieve sfeer waarin we elkaar stimuleren.',
        ],
        'Speelsheid en humor' => [
            'Ik ervaar ruimte voor humor en ontspanning tijdens het werk.',
            'Ik draag actief bij aan een positieve en energieke werksfeer.',
            'Ik gebruik humor om spanningen te relativeren en samen verder te komen.',
            'Ik verken vraagstukken regelmatig op een creatieve of vernieuwende manier om nieuwe ideeën te ontwikkelen.',
            'Ik ervaar dat de sfeer binnen ons team mij helpt om nieuwe ideeën en mogelijkheden te ontdekken.',
        ],
        'Dialoog' => [
            'Ik onderzoek verschillende standpunten voordat ik een besluit neem.',
            'Ik voel mij vrij om een afwijkende mening naar voren te brengen.',
            'Ik ervaar kritische vragen als een waardevolle bijdrage aan het gesprek.',
            'Ik zoek bewust naar verschillende perspectieven om vraagstukken beter te begrijpen.',
            'Ik ervaar dat verschillende meningen ons helpen om tot betere keuzes en oplossingen te komen.',
        ],
        'Conflict' => [
            'Ik bespreek meningsverschillen open en respectvol.',
            'Ik maak spanningen of conflicten tijdig bespreekbaar.',
            'Ik blijf, ook bij verschillen van inzicht, gericht op samenwerking en gezamenlijke doelen.',
            'Ik ervaar dat verschillende opvattingen binnen ons team zelden leiden tot persoonlijke verwijten of irritaties.',
            'Ik gebruik verschillen van inzicht om tot betere oplossingen te komen.',
        ],
        'Risico nemen' => [
            'Ik krijg ruimte om nieuwe werkwijzen uit te proberen.',
            'Ik zie experimenteren als een belangrijk onderdeel van leren en verbeteren.',
            'Wanneer een idee niet het gewenste resultaat oplevert, kijk ik vooral naar wat ik ervan kan leren.',
            'Ik probeer nieuwe ideeën eerst op kleine schaal uit voordat ze breed worden ingevoerd.',
            'Ik word aangemoedigd om kansen te benutten, ook als het resultaat nog onzeker is.',
        ],
        'Tijd voor ideeën' => [
            'Ik heb voldoende ruimte om na te denken over verbeteringen en vernieuwingen.',
            'Ik neem regelmatig de tijd om te reflecteren op mijn werk en mijn resultaten.',
            'Ik geef nieuwe ideeën aandacht, ook wanneer de werkdruk hoog is.',
            'Ik heb de mogelijkheid om nieuwe mogelijkheden te onderzoeken en te verkennen.',
            'Ik zie dat goede ideeën niet alleen worden bedacht, maar ook tijd en aandacht krijgen om verder ontwikkeld te worden.',
        ],
        'Uitdaging' => [
            'Ik begrijp hoe mijn werkzaamheden bijdragen aan de doelen van ons team.',
            'Ik werk aan doelen die ambitieus en haalbaar zijn.',
            'Ik zet mij actief in om gezamenlijke resultaten te behalen.',
            'Ik voel mij verantwoordelijk voor het succes van het team.',
            'Ik weet duidelijk waar we als team naartoe werken.',
        ],
    ];

    public function getDescription(): string
    {
        return 'Seeds the Dutch climate dimensions and questions in circle order starting with Freedom.';
    }

    public function up(Schema $schema): void
    {
        $names = array_keys(self::DIMENSIONS);
        $dimensionValues = implode(', ', array_fill(0, count($names), '(?)'));
        $this->addSql(
            sprintf('INSERT INTO dimension (name) VALUES %s', $dimensionValues),
            $names,
        );

        foreach (self::DIMENSIONS as $name => $questions) {
            $questionValues = [];
            $parameters = [];
            foreach ($questions as $title) {
                $questionValues[] = '(?, (SELECT id FROM dimension WHERE name = ?))';
                $parameters[] = $title;
                $parameters[] = $name;
            }

            $this->addSql(
                sprintf(
                    'INSERT INTO question (title, dimension_id) VALUES %s',
                    implode(', ', $questionValues),
                ),
                $parameters,
            );
        }
    }

    public function down(Schema $schema): void
    {
        $names = array_keys(self::DIMENSIONS);
        $placeholders = implode(', ', array_fill(0, count($names), '?'));
        $this->addSql(
            sprintf(
                'DELETE FROM question WHERE dimension_id IN (SELECT id FROM dimension WHERE name IN (%s))',
                $placeholders,
            ),
            $names,
        );
        $this->addSql(
            sprintf('DELETE FROM dimension WHERE name IN (%s)', $placeholders),
            $names,
        );
    }
}
