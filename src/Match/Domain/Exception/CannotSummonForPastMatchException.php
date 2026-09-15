<?php

namespace App\Match\Domain\Exception;

use App\Match\Domain\MatchId;

final class CannotSummonForPastMatchException extends \DomainException
{
    public static function forMatch(MatchId $matchId): self
    {
        return new self("Cannot summon a player for match \"{$matchId->toString()}\" because it has already been played");
    }
}
