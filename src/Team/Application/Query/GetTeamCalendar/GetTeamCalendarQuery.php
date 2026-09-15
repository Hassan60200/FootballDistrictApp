<?php

namespace App\Team\Application\Query\GetTeamCalendar;

use App\Team\Domain\TeamId;

final class GetTeamCalendarQuery
{
    public function __construct(
        public readonly TeamId $teamId,
    ) {}
}
