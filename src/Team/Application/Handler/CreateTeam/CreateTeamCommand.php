<?php

namespace App\Team\Application\Handler\CreateTeam;

use App\Team\Domain\TeamCategory;

final class CreateTeamCommand
{
    public function __construct(
        public readonly string $name,
        public readonly TeamCategory $category,
    ) {}
}
