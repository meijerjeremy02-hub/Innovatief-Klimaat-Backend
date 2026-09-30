<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Dto\SubmissionReceipt;
use App\Dto\SubmitAnswersInput;
use App\Entity\Answer;
use App\Entity\Submission;
use App\Repository\DimensionRepository;
use App\Repository\QuestionRepository;
use App\Repository\TeamRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @implements ProcessorInterface<SubmitAnswersInput, SubmissionReceipt>
 */
final readonly class SubmitAnswersProcessor implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private TeamRepository $teams,
        private DimensionRepository $dimensions,
        private QuestionRepository $questions,
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): SubmissionReceipt
    {
        if (!$data instanceof SubmitAnswersInput) {
            throw new \LogicException('The submit operation requires a SubmitAnswersInput payload.');
        }

        $team = $this->teams->findOneBy(['code' => $data->teamCode]);
        if ($team === null) {
            throw new NotFoundHttpException('The team code was not found.');
        }

        $dimension = $this->dimensions->find($data->dimensionId);
        if ($dimension === null) {
            throw new NotFoundHttpException('The dimension was not found.');
        }

        $questionIds = array_column($data->answers, 'questionId');
        if (count(array_unique($questionIds)) !== 5) {
            throw new BadRequestHttpException('Each of the five questions must be answered exactly once.');
        }

        $dimensionQuestions = $dimension->getQuestions()->toArray();
        if (count($dimensionQuestions) !== 5) {
            throw new BadRequestHttpException('The selected dimension must have exactly five questions.');
        }

        $dimensionQuestionIds = array_map(
            static fn ($question): int => $question->getId(),
            $dimensionQuestions,
        );
        sort($questionIds);
        sort($dimensionQuestionIds);
        if ($questionIds !== $dimensionQuestionIds) {
            throw new BadRequestHttpException('Answers must include all five questions from the selected dimension.');
        }

        $questionEntities = $this->questions->findBy(['id' => $questionIds]);
        $questionsById = [];
        foreach ($questionEntities as $question) {
            $questionsById[$question->getId()] = $question;
        }

        $submission = new Submission();
        $submission->setTeam($team)->setDimension($dimension);

        $this->entityManager->wrapInTransaction(function () use ($data, $questionsById, $submission): void {
            foreach ($data->answers as $answerInput) {
                $answer = (new Answer())
                    ->setQuestion($questionsById[$answerInput['questionId']])
                    ->setValue($answerInput['value']);
                $submission->addAnswer($answer);
            }

            $this->entityManager->persist($submission);
        });

        return new SubmissionReceipt(
            $submission->getId(),
            $team->getId(),
            $dimension->getId(),
        );
    }
}
