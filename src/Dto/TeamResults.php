<?php

namespace App\Dto;

final readonly class TeamResults
{
    /**
     * @param array{id: int, name: string} $category
     * @param list<array{
     *     dimension_id: int,
     *     dimension_name: string,
     *     average: float|null,
     *     participant_count: int,
     *     questions: list<array{question_id: int, question_title: string, average: float|null, response_count: int}>
     * }> $dimensions
     */
    public function __construct(
        public int $team_id,
        public string $team_code,
        public string $team_name,
        public array $category,
        public array $dimensions,
    ) {
    }
}
