<?php

namespace App\Match\Domain\Repository;


use App\Match\Domain\FootballMatch;
use App\Match\Domain\MatchId;

interface MatchRepositoryInterface
{
    public function findById(MatchId $id): ?FootballMatch;
    public function save(FootballMatch $match): void;
    public function findAll(): array;
}
