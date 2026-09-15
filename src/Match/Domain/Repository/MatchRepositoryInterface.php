<?php

namespace App\Match\Domain\Repository;

use App\Match\Domain\FootballMatch;
use App\Match\Domain\MatchId;
use App\Team\Domain\TeamId;

interface MatchRepositoryInterface
{
    public function findById(MatchId $id): ?FootballMatch;
    public function save(FootballMatch $match): void;
    public function findAll(): array;
    public function findByTeam(TeamId $teamId): array;
    public function findByWeek(\DateTimeImmutable $weekOf): array;
}
