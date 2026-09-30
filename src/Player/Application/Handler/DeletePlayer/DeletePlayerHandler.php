<?php

namespace App\Player\Application\Handler\DeletePlayer;

use App\Player\Domain\Exception\PlayerNotFoundException;
use App\Player\Domain\Repository\PlayerRepositoryInterface;

final class DeletePlayerHandler
{
    public function __construct(
        private readonly PlayerRepositoryInterface $players,
    ) {}

    public function handle(DeletePlayerCommand $command): void
    {
        $player = $this->players->findById($command->playerId);
        if ($player === null) {
            throw PlayerNotFoundException::withId($command->playerId);
        }

        $this->players->delete($player);
    }
}
