<?php

namespace App\Player\Domain\Exception;

use App\Player\Domain\PlayerId;

final class PlayerAlreadyDeactivatedException extends \DomainException
{
    public static function withId(PlayerId $id): self
    {
        return new self("Player with id \"{$id->toString()}\" is already deactivated");
    }
}
