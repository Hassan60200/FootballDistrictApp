<?php

namespace App\Match\Application\Handler\CancelMatch;

use App\Match\Domain\MatchId;

final class CancelMatchCommand
{
    public function __construct(
        public readonly MatchId $matchId,
    ) {}
}
