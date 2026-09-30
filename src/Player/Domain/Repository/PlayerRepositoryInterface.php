<?php

namespace App\Player\Domain\Repository;

use App\Player\Domain\Player;
use App\Player\Domain\PlayerId;

interface PlayerRepositoryInterface
{
    public function findById(PlayerId $id): ?Player;
    public function save(Player $player): void;
    public function findAll(): array;
    public function delete(Player $player): void;

}
