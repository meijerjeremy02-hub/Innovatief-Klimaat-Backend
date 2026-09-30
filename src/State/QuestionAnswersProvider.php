<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\ApiResource\QuestionAnswers;
use App\Repository\AnswerRepository;
use App\Repository\QuestionRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @implements ProviderInterface<QuestionAnswers>
 */
final readonly class QuestionAnswersProvider implements ProviderInterface
{
    public function __construct(
        private QuestionRepository $questions,
        private AnswerRepository $answers,
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): QuestionAnswers
    {
        $question = $this->questions->find($uriVariables['questionId'] ?? null);
        if ($question === null) {
            throw new NotFoundHttpException('The question was not found.');
        }

        $result = [];
        foreach ($this->answers->findBy(['question' => $question], ['id' => 'ASC']) as $answer) {
            $submission = $answer->getSubmission();
            $result[] = [
                'answer_id' => $answer->getId(),
                'value' => $answer->getValue(),
                'response_id' => $submission->getId(),
                'team_id' => $submission->getTeam()->getId(),
            ];
        }

        return new QuestionAnswers(
            $question->getId(),
            $question->getDimension()?->getId(),
            $result,
        );
    }
}
