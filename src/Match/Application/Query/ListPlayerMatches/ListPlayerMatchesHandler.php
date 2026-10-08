<?php

namespace App\Match\Application\Query\ListPlayerMatches;

use App\Match\Domain\FootballMatch;
use App\Match\Domain\Repository\MatchRepositoryInterface;

final class ListPlayerMatchesHandler
{
    public function __construct(
        private readonly MatchRepositoryInterface $matches,
    ) {}

    /** @return FootballMatch[] du plus récent au plus ancien */
    public function handle(ListPlayerMatchesQuery $query): array
    {
        $matches = array_filter(
            $this->matches->findAll(),
            static fn (FootballMatch $match) => $match->isPlayerAlreadySummoned($query->playerId),
        );

        usort(
            $matches,
            static fn (FootballMatch $a, FootballMatch $b) => $b->getScheduledAt()->toDateTimeImmutable() <=> $a->getScheduledAt()->toDateTimeImmutable(),
        );

        return $matches;
    }
}
