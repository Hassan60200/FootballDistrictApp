<?php

namespace App\Player\Application\Handler\RegisterPlayer;

use App\Player\Domain\Position;

final class RegisterPlayerCommand
{
    public function __construct(
        public readonly string $firstName,
        public readonly string $lastName,
        public readonly int $age,
        public readonly string $email,
        public readonly Position $position,
    ) {}
}
