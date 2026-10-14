<?php

namespace App\Match\Application\Query\ListPlayerMatches;

use App\Player\Domain\PlayerId;

final class ListPlayerMatchesQuery
{
    public function __construct(
        public readonly PlayerId $playerId,
    ) {}
}
