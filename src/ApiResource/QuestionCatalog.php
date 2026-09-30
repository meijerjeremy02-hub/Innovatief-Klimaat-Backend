<?php

namespace App\ApiResource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use App\State\QuestionCatalogProvider;

#[ApiResource(operations: [
    new Get(
        uriTemplate: '/questions',
        provider: QuestionCatalogProvider::class,
    ),
])]
final readonly class QuestionCatalog
{
    /**
     * @param list<array{id: int, name: string, questions: list<array{id: int, title: string}>}> $dimensions
     */
    public function __construct(public array $dimensions)
    {
    }
}
