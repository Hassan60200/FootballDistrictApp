<?php

namespace App\Player\Application\Handler\DeactivatePlayer;

use App\Player\Domain\Exception\PlayerNotFoundException;
use App\Player\Domain\Repository\PlayerRepositoryInterface;

final class DeactivatePlayerHandler
{
    public function __construct(
        private readonly PlayerRepositoryInterface $players,
    ) {}

    public function handle(DeactivatePlayerCommand $command): void
    {
        $player = $this->players->findById($command->playerId);

        if ($player === null) {
            throw PlayerNotFoundException::withId($command->playerId);
        }

        $player->deactivate();
        $this->players->save($player);
    }
}
