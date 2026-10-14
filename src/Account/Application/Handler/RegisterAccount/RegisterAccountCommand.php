<?php

declare(strict_types=1);

namespace App\Account\Application\RegisterAccount;

use App\Account\Domain\ProfileType;

final readonly class RegisterAccountCommand
{
    public function __construct(
        public string $email,
        #[\SensitiveParameter]
        public string $password,
        public ProfileType $profileType,
        public string $profileIdentifier,
    ) {
    }
}
