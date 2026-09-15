<?php

namespace App\Match\Application\Query\ListMatches;

use App\Match\Domain\Repository\MatchRepositoryInterface;

final class ListMatchesHandler
{
    public function __construct(
        private readonly MatchRepositoryInterface $matches,
    ) {}

    public function handle(ListMatchesQuery $query): array
    {
        return $this->matches->findAll();
    }
}
