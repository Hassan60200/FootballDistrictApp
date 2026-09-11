<?php

namespace App\Player\Domain\Exception;

use App\Player\Domain\PlayerId;

final class PlayerNotFoundException extends \DomainException
{
    public static function withId(PlayerId $id): self
    {
        return new self("Player with id \"{$id->toString()}\" not found");
    }
}
