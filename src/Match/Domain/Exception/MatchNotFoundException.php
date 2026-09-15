<?php

namespace App\Match\Domain\Exception;

use App\Match\Domain\MatchId;

final class MatchNotFoundException extends \DomainException
{
    public static function withId(MatchId $id): self
    {
        return new self("Match with id \"{$id->toString()}\" not found");
    }
}
