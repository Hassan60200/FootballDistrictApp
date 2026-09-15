<?php

namespace App\Match\Domain\Exception;

use App\Player\Domain\PlayerId;

final class PlayerAlreadySummonedThisWeekException extends \DomainException
{
    public static function forPlayer(PlayerId $playerId): self
    {
        return new self("Player \"{$playerId->toString()}\" is already summoned for another match this week");
    }
}
