<?php

namespace App\Coach\Domain\Repository;

use App\Coach\Domain\Coach;
use App\Coach\Domain\CoachId;

interface CoachRepositoryInterface
{
    public function findById(CoachId $id): ?Coach;
    public function save(Coach $coach): void;
    public function findAll(): array;
    public function delete(Coach $coach): void;

}
