<?php

namespace App\Match\Domain\Exception;

use App\Match\Domain\MatchId;
use App\Player\Domain\PlayerId;

final class PlayerAlreadySummonedException extends \DomainException
{
    public static function forMatch(MatchId $matchId, PlayerId $playerId): self
    {
        return new self("Player \"{$playerId->toString()}\" is already summoned for match \"{$matchId->toString()}\"");
    }
}
