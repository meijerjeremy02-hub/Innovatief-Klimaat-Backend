<?php

namespace App\Dto;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\State\SubmitAnswersProcessor;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(operations: [
    new Post(
        uriTemplate: '/submit',
        processor: SubmitAnswersProcessor::class,
        output: SubmissionReceipt::class,
        status: 201,
    ),
])]
final readonly class SubmitAnswersInput
{
    /**
     * @param list<array{questionId: int, value: int}> $answers
     */
    public function __construct(
        #[Assert\NotBlank]
        public string $teamCode,
        #[Assert\Positive]
        public int $dimensionId,
        #[Assert\Count(exactly: 5)]
        #[Assert\All([
            new Assert\Collection(
                fields: [
                    'questionId' => [new Assert\Type('integer'), new Assert\Positive()],
                    'value' => [new Assert\Type('integer'), new Assert\Range(min: 1, max: 5)],
                ],
                allowExtraFields: false,
                allowMissingFields: false,
            ),
        ])]
        public array $answers,
    ) {
    }
}
