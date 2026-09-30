<?php

namespace App\Player\Application\Handler\DeletePlayer;

use App\Player\Domain\PlayerId;

final class DeletePlayerCommand
{
    public function __construct(
        public readonly PlayerId $playerId,
    ) {}
}
