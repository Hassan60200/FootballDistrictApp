<?php

namespace App\Coach\Domain\Exception;

use App\Coach\Domain\CoachId;

final class CoachAlreadyDeactivatedException extends \DomainException
{
    public static function withId(CoachId $id): self
    {
        return new self("Coach with id \"{$id->toString()}\" is already deactivated");
    }
}
