<?php

namespace App\Team\Application\Handler\CreateTeam;

use App\Team\Domain\Repository\TeamRepositoryInterface;
use App\Team\Domain\Team;
use App\Team\Domain\TeamId;

final class CreateTeamHandler
{
    public function __construct(
        private readonly TeamRepositoryInterface $teams,
    ) {}

    public function handle(CreateTeamCommand $command): TeamId
    {
        $team = Team::create($command->name, $command->category);
        $this->teams->save($team);
        return $team->getId();
    }
}
