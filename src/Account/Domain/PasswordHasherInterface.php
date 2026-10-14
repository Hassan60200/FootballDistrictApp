<?php

declare(strict_types=1);

namespace App\Account\Domain;

interface PasswordHasherInterface
{
    public function hash(PlainPassword $plainPassword): HashedPassword;
}
