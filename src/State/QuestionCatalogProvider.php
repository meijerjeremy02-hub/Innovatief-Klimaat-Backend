<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\ApiResource\QuestionCatalog;
use App\Repository\DimensionRepository;

/**
 * @implements ProviderInterface<QuestionCatalog>
 */
final readonly class QuestionCatalogProvider implements ProviderInterface
{
    public function __construct(private DimensionRepository $dimensions)
    {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): QuestionCatalog
    {
        $result = [];
        foreach ($this->dimensions->findBy([], ['position' => 'ASC']) as $dimension) {
            $questions = [];
            foreach ($dimension->getQuestions() as $question) {
                $questions[] = [
                    'id' => $question->getId(),
                    'title' => $question->getTitle(),
                ];
            }

            $result[] = [
                'id' => $dimension->getId(),
                'name' => $dimension->getName(),
                'questions' => $questions,
            ];
        }

        return new QuestionCatalog($result);
    }
}
