<?php

namespace App\Team\Application\Handler\DeleteTeam;

use App\Team\Domain\Exception\TeamNotFoundException;
use App\Team\Domain\Repository\TeamRepositoryInterface;

final class DeleteTeamHandler
{
    public function __construct(
        private readonly TeamRepositoryInterface $teams,
    ) {}

    public function handle(DeleteTeamCommand $command): void
    {
        $team = $this->teams->findById($command->teamId);
        if ($team === null) {
            throw TeamNotFoundException::withId($command->teamId);
        }

        $this->teams->delete($team);
    }
}
