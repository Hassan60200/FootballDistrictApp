<?php

namespace App\Match\Domain;

use App\Match\Domain\Repository\MatchRepositoryInterface;
use App\Player\Domain\PlayerId;

final class PlayerWeeklyAvailabilityChecker
{
    public function __construct(
        private readonly MatchRepositoryInterface $matches,
    ) {}

    public function isAvailableForWeek(PlayerId $playerId, \DateTimeImmutable $weekOf): bool
    {
        $matchesThisWeek = $this->matches->findByWeek($weekOf);

        foreach ($matchesThisWeek as $match) {
            if ($match->isPlayerSummoned($playerId)) {
                return false;
            }
        }

        return true;
    }
}
