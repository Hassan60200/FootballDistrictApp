<?php

namespace App\Match\Application\Handler\UpdateMatch;

use App\Match\Domain\MatchId;

final class UpdateMatchCommand
{
    public function __construct(
        public readonly MatchId $matchId,
        public readonly \DateTimeImmutable $date,
        public readonly string $time,
        public readonly string $competitionName,
        public readonly string $awayClubName,
    ) {}
}
