<?php

namespace App\ApiResource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use App\State\QuestionAnswersProvider;

#[ApiResource(operations: [
    new Get(
        uriTemplate: '/questions/{questionId}/answers',
        requirements: ['questionId' => '\d+'],
        provider: QuestionAnswersProvider::class,
    ),
])]
final readonly class QuestionAnswers
{
    /**
     * @param list<array{answer_id: int, value: int, response_id: int, team_id: int}> $answers
     */
    public function __construct(
        public int $question_id,
        public ?int $dimension_id,
        public array $answers,
    ) {
    }
}
