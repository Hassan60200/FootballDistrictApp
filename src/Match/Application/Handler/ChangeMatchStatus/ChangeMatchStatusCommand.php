<?php

namespace App\Match\Application\Handler\ChangeMatchStatus;

use App\Match\Domain\MatchId;
use App\Match\Domain\MatchStatus;

final class ChangeMatchStatusCommand
{
    public function __construct(
        public readonly MatchId $matchId,
        public readonly MatchStatus $status,
    ) {}
}
