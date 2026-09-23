<?php

namespace App\Team\Application\Handler\DeleteTeam;

use App\Team\Domain\TeamId;

final class DeleteTeamCommand
{
    public function __construct(
        public readonly TeamId $teamId,
    ) {}
}
