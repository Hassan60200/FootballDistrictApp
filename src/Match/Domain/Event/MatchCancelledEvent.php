<?php

namespace App\Match\Domain\Event;

use App\Match\Domain\MatchId;

final class MatchCancelledEvent
{
    public function __construct(
        public readonly MatchId $matchId
    ) {}
}
