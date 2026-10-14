<?php

declare(strict_types=1);

namespace App\Account\Domain\Exception;

use App\Account\Domain\AccountId;

final class AccountAlreadyDeactivatedException extends \DomainException
{
    public static function withId(AccountId $id): self
    {
        return new self(sprintf('Account "%s" is already deactivated.', $id->toString()));
    }
}
