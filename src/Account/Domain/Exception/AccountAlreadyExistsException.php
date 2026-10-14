<?php

declare(strict_types=1);

namespace App\Account\Domain\Exception;

use App\Account\Domain\Email;
use App\Account\Domain\ProfileReference;

final class AccountAlreadyExistsException extends \DomainException
{
    public static function withEmail(Email $email): self
    {
        return new self(sprintf('An account already exists for email "%s".', $email->toString()));
    }

    public static function forProfile(ProfileReference $profile): self
    {
        return new self(sprintf(
            'An account already exists for profile %s "%s".',
            $profile->type()->value,
            $profile->identifier(),
        ));
    }
}
