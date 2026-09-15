<?php

namespace App\Team\Application\Query\GetTeamCalendar;

use App\Match\Domain\Repository\MatchRepositoryInterface;

final class GetTeamCalendarHandler
{
    public function __construct(
        private readonly MatchRepositoryInterface $matches,
    ) {}

    public function handle(GetTeamCalendarQuery $query): array
    {
        return $this->matches->findByTeam($query->teamId);
    }
}
