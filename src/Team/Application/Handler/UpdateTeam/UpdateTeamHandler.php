<?php

namespace App\Team\Application\Handler\UpdateTeam;

use App\Team\Domain\Exception\TeamNotFoundException;
use App\Team\Domain\Repository\TeamRepositoryInterface;

final class UpdateTeamHandler
{
    public function __construct(
        private readonly TeamRepositoryInterface $teams,
    ) {}

    public function handle(UpdateTeamCommand $command): void
    {
        $team = $this->teams->findById($command->teamId);
        if ($team === null) {
            throw TeamNotFoundException::withId($command->teamId);
        }

        $team->rename($command->name, $command->category);
        $this->teams->save($team);
    }
}
