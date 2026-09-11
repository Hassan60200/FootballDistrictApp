<?php

namespace App\Player\Application\Handler\RegisterPlayer;

use App\Player\Domain\Email;
use App\Player\Domain\Player;
use App\Player\Domain\PlayerId;
use App\Player\Domain\Repository\PlayerRepositoryInterface;

final class RegisterPlayerHandler
{
    public function __construct(
        private readonly PlayerRepositoryInterface $players,
    ) {}

    public function handle(RegisterPlayerCommand $command): PlayerId
    {
        $player = Player::register(
            firstName: $command->firstName,
            lastName: $command->lastName,
            age: $command->age,
            email: Email::fromString($command->email),
            position: $command->position,
        );

        $this->players->save($player);

        return $player->getId();
    }
}
