<?php

namespace App\Match\Domain\Exception;

use App\Match\Domain\MatchId;

final class MatchAlreadyCancelledException extends \DomainException
{
    public static function forMatch(MatchId $matchId): self
    {
        return new self("Match \"{$matchId->toString()}\" is already cancelled");
    }
}
