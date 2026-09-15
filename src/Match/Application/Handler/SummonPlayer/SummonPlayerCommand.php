<?php

namespace App\Match\Application\Handler\SummonPlayer;

use App\Match\Domain\MatchId;
use App\Player\Domain\PlayerId;

final class SummonPlayerCommand
{
    public function __construct(
        public readonly MatchId $matchId,
        public readonly PlayerId $playerId,
    ) {}
}
