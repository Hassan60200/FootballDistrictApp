<?php

declare(strict_types=1);

namespace App\Account\Domain\Exception;

use App\Account\Domain\ProfileReference;

final class ProfileNotFoundException extends \DomainException
{
    public static function forProfile(ProfileReference $profile): self
    {
        return new self(sprintf(
            'No %s profile found with identifier "%s".',
            $profile->type()->value,
            $profile->identifier(),
        ));
    }
}
