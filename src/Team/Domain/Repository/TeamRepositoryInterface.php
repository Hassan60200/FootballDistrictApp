<?php

namespace App\Team\Domain\Repository;

use App\Team\Domain\Team;
use App\Team\Domain\TeamId;

interface TeamRepositoryInterface
{
    public function findById(TeamId $id): ?Team;
    public function save(Team $team): void;
    public function findAll(): array;
}
