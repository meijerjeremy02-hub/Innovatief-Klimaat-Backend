<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Dto\CategoryResults;
use App\Dto\PersonResult;
use App\Dto\TeamResults;
use App\Dto\TotalResults;
use App\Entity\Answer;
use App\Entity\Category;
use App\Entity\Dimension;
use App\Entity\Team;
use App\Repository\CategoryRepository;
use App\Repository\DimensionRepository;
use App\Repository\SubmissionRepository;
use App\Repository\TeamRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @implements ProviderInterface<PersonResult|TeamResults|CategoryResults|TotalResults>
 */
final readonly class ResultsProvider implements ProviderInterface
{
    public function __construct(
        private SubmissionRepository $submissions,
        private CategoryRepository $categories,
        private TeamRepository $teams,
        private DimensionRepository $dimensions,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): PersonResult|TeamResults|CategoryResults|TotalResults
    {
        return match ($operation->getUriTemplate()) {
            '/results/person/{responseId}' => $this->person((int) $uriVariables['responseId']),
            '/results/team/{teamCode}' => $this->team((string) $uriVariables['teamCode']),
            '/results/category/{categoryId}' => $this->category((int) $uriVariables['categoryId']),
            '/results/total' => new TotalResults($this->averages()),
            default => throw new \LogicException('Unsupported results operation.'),
        };
    }

    private function person(int $responseId): PersonResult
    {
        $submission = $this->submissions->find($responseId);
        if ($submission === null) {
            throw new NotFoundHttpException('The response was not found.');
        }

        $answers = [];
        $total = 0;
        foreach ($submission->getAnswers() as $answer) {
            $total += $answer->getValue();
            $answers[] = [
                'question_id' => $answer->getQuestion()->getId(),
                'value' => $answer->getValue(),
            ];
        }

        return new PersonResult(
            $submission->getId(),
            $submission->getTeam()->getId(),
            [
                'id' => $submission->getDimension()->getId(),
                'name' => $submission->getDimension()->getName(),
            ],
            count($answers) === 0 ? null : $total / count($answers),
            $answers,
        );
    }

    private function team(string $teamCode): TeamResults
    {
        $team = $this->teams->findOneBy(['code' => $teamCode]);
        if ($team === null) {
            throw new NotFoundHttpException('The team was not found.');
        }

        return new TeamResults(
            $team->getId(),
            $team->getCode(),
            $team->getName(),
            [
                'id' => $team->getCategory()->getId(),
                'name' => $team->getCategory()->getName(),
            ],
            $this->averages($team),
        );
    }

    private function category(int $categoryId): CategoryResults
    {
        $category = $this->categories->find($categoryId);
        if ($category === null) {
            throw new NotFoundHttpException('The category was not found.');
        }

        return new CategoryResults(
            $category->getId(),
            $category->getName(),
            $this->averages(null, $category),
        );
    }

    /**
     * @return list<array{
     *     dimension_id: int,
     *     dimension_name: string,
     *     average: float|null,
     *     participant_count: int,
     *     questions: list<array{question_id: int, question_title: string, average: float|null, response_count: int}>
     * }>
     */
    private function averages(?Team $team = null, ?Category $category = null): array
    {
        $dimensionResults = [];
        foreach ($this->dimensions->findBy([], ['position' => 'ASC', 'id' => 'ASC']) as $dimension) {
            $dimensionId = $dimension->getId();
            $questions = [];

            foreach ($dimension->getQuestions() as $question) {
                $questions[$question->getId()] = [
                    'question_id' => $question->getId(),
                    'question_title' => $question->getTitle(),
                    'average' => null,
                    'response_count' => 0,
                    'answer_sum' => 0,
                ];
            }

            $dimensionResults[$dimensionId] = [
                'dimension_id' => $dimensionId,
                'dimension_name' => $dimension->getName(),
                'average' => null,
                'participant_count' => 0,
                'answer_sum' => 0,
                'answer_count' => 0,
                'questions' => $questions,
            ];
        }

        $query = $this->entityManager->createQueryBuilder()
            ->select(
                'dimension.id AS dimensionId',
                'question.id AS questionId',
                'SUM(answer.value) AS answerSum',
                'COUNT(answer.id) AS answerCount',
                'COUNT(DISTINCT submission.id) AS participantCount',
            )
            ->from(Answer::class, 'answer')
            ->join('answer.submission', 'submission')
            ->join('answer.question', 'question')
            ->join('question.dimension', 'dimension')
            ->join('submission.team', 'team')
            ->groupBy('dimension.id, question.id')
            ->orderBy('dimension.position', 'ASC')
            ->addOrderBy('question.id', 'ASC');

        if ($team !== null) {
            $query
                ->andWhere('team.id = :team')
                ->setParameter('team', $team);
        }

        if ($category !== null) {
            $query
                ->andWhere('team.category = :category')
                ->setParameter('category', $category);
        }

        foreach ($query->getQuery()->getArrayResult() as $row) {
            $dimensionId = (int) $row['dimensionId'];
            $questionId = (int) $row['questionId'];
            $answerSum = (int) $row['answerSum'];
            $answerCount = (int) $row['answerCount'];

            if (!isset($dimensionResults[$dimensionId]['questions'][$questionId])) {
                continue;
            }

            $dimensionResults[$dimensionId]['questions'][$questionId]['average'] = $answerSum / $answerCount;
            $dimensionResults[$dimensionId]['questions'][$questionId]['response_count'] = $answerCount;
            $dimensionResults[$dimensionId]['questions'][$questionId]['answer_sum'] = $answerSum;

            $dimensionResults[$dimensionId]['answer_sum'] += $answerSum;
            $dimensionResults[$dimensionId]['answer_count'] += $answerCount;
            $dimensionResults[$dimensionId]['participant_count'] = max(
                $dimensionResults[$dimensionId]['participant_count'],
                (int) $row['participantCount'],
            );
        }

        foreach ($dimensionResults as &$dimensionResult) {
            if ($dimensionResult['answer_count'] > 0) {
                $dimensionResult['average'] = $dimensionResult['answer_sum'] / $dimensionResult['answer_count'];
            }

            foreach ($dimensionResult['questions'] as &$questionResult) {
                unset($questionResult['answer_sum']);
            }
            unset($questionResult);

            $dimensionResult['questions'] = array_values($dimensionResult['questions']);
            unset($dimensionResult['answer_sum'], $dimensionResult['answer_count']);
        }
        unset($dimensionResult);

        return array_values($dimensionResults);
    }
}
