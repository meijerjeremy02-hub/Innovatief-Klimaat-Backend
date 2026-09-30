<?php

namespace App\Dto;

final readonly class PersonResult
{
    /**
     * @param list<array{question_id: int, value: int}> $answers
     * @param array{id: int, name: string} $dimension
     */
    public function __construct(
        public int $response_id,
        public int $team_id,
        public array $dimension,
        public ?float $average,
        public array $answers,
    ) {
    }
}
