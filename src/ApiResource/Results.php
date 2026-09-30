<?php

namespace App\ApiResource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use App\Dto\CategoryResults;
use App\Dto\PersonResult;
use App\Dto\TeamResults;
use App\Dto\TotalResults;
use App\State\ResultsProvider;

#[ApiResource(operations: [
    new Get(
        uriTemplate: '/results/team/{teamCode}',
        requirements: ['teamCode' => '[^/]+'],
        provider: ResultsProvider::class,
        output: TeamResults::class,
    ),
    new Get(
        uriTemplate: '/results/person/{responseId}',
        requirements: ['responseId' => '\d+'],
        provider: ResultsProvider::class,
        output: PersonResult::class,
    ),
    new Get(
        uriTemplate: '/results/category/{categoryId}',
        requirements: ['categoryId' => '\d+'],
        provider: ResultsProvider::class,
        output: CategoryResults::class,
    ),
    new Get(
        uriTemplate: '/results/total',
        provider: ResultsProvider::class,
        output: TotalResults::class,
    ),
])]
final class Results
{
}
