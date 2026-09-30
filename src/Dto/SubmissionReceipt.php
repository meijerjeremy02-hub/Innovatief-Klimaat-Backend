<?php

namespace App\Dto;

final readonly class SubmissionReceipt
{
    public function __construct(
        public int $response_id,
        public int $team_id,
        public int $dimension_id,
    ) {
    }
}
