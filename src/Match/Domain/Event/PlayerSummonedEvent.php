<?php

namespace App\Match\Domain\Event;

use App\Match\Domain\MatchId;
use App\Player\Domain\PlayerId;

final class PlayerSummonedEvent
{
    public function __construct(
        public readonly MatchId $matchId,
        public readonly PlayerId $playerId,
    ) {}
}
