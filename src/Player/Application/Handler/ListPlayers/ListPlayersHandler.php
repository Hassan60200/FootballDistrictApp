<?php

namespace App\Player\Application\Handler\ListPlayers;

use App\Player\Domain\Repository\PlayerRepositoryInterface;

final class ListPlayersHandler
{
    public function __construct(
        private readonly PlayerRepositoryInterface $players,
    ) {}

    public function handle(ListPlayersQuery $query): array
    {
        return $this->players->findAll();
    }
}
