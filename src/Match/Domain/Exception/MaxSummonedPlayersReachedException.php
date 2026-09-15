<?php

namespace App\Match\Domain\Exception;

use App\Match\Domain\MatchId;

final class MaxSummonedPlayersReachedException extends \DomainException
{
    public static function forMatch(MatchId $matchId): self
    {
        return new self("Match \"{$matchId->toString()}\" already has the maximum of 14 summoned players");
    }
}
