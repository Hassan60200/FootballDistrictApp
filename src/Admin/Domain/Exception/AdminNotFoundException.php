<?php

namespace App\Admin\Domain\Exception;

use App\Admin\Domain\AdminId;

final class AdminNotFoundException extends \DomainException
{
    public static function withId(AdminId $id): self
    {
        return new self("Admin with id \"{$id->toString()}\" not found");
    }
}
