<?php

namespace App\Team\Domain\Exception;

use App\Team\Domain\TeamId;

final class TeamNotFoundException extends \DomainException
{
    public static function withId(TeamId $id): self
    {
        return new self("Team with id \"{$id->toString()}\" not found");
    }
}
