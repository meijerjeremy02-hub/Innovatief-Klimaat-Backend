<?php

namespace App\Dto;

final readonly class CategoryResults
{
    /**
     * @param list<array{
     *     dimension_id: int,
     *     dimension_name: string,
     *     average: float|null,
     *     participant_count: int,
     *     questions: list<array{question_id: int, question_title: string, average: float|null, response_count: int}>
     * }> $dimensions
     */
    public function __construct(
        public int $category_id,
        public string $category_name,
        public array $dimensions,
    ) {
    }
}
