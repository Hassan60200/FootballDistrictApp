<?php

namespace App\Player\Application\Handler\UpdatePlayer;

use App\Player\Domain\PlayerId;
use App\Player\Domain\Position;

final class UpdatePlayerCommand
{
    public function __construct(
        public readonly PlayerId $playerId,
        public readonly string $firstName,
        public readonly string $lastName,
        public readonly int $age,
        public readonly string $email,
        public readonly Position $position,
    ) {}
}
