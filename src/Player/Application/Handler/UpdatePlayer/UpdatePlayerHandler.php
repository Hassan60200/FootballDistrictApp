<?php

namespace App\Player\Application\Handler\UpdatePlayer;

use App\Player\Domain\Email;
use App\Player\Domain\Exception\PlayerNotFoundException;
use App\Player\Domain\Repository\PlayerRepositoryInterface;

final class UpdatePlayerHandler
{
    public function __construct(
        private readonly PlayerRepositoryInterface $players,
    ) {}

    public function handle(UpdatePlayerCommand $command): void
    {
        $player = $this->players->findById($command->playerId);
        if ($player === null) {
            throw PlayerNotFoundException::withId($command->playerId);
        }

        $player->update(
            $command->firstName,
            $command->lastName,
            $command->age,
            Email::fromString($command->email),
            $command->position,
        );
        $this->players->save($player);
    }
}
