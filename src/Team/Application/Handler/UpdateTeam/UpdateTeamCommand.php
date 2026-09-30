<?php

namespace App\Team\Application\Handler\UpdateTeam;

use App\Team\Domain\TeamCategory;
use App\Team\Domain\TeamId;

final class UpdateTeamCommand
{
    public function __construct(
        public readonly TeamId $teamId,
        public readonly string $name,
        public readonly TeamCategory $category,
    ) {}
}
