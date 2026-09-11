<?php

namespace App\Player\Application\Handler\DeactivatePlayer;

use App\Player\Domain\PlayerId;

final class DeactivatePlayerCommand
{
    public function __construct(
        public readonly PlayerId $playerId,
    ) {}
}
