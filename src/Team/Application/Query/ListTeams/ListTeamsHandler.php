<?php

namespace App\Team\Application\Query\ListTeams;

use App\Team\Domain\Repository\TeamRepositoryInterface;

final class ListTeamsHandler
{
    public function __construct(
        private readonly TeamRepositoryInterface $teams,
    ) {}

    public function handle(ListTeamsQuery $query): array
    {
        return $this->teams->findAll();
    }
}
