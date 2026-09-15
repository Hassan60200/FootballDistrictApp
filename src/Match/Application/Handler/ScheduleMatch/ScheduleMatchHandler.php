<?php

namespace App\Match\Application\Handler\ScheduleMatch;

use App\Match\Domain\ClubInfo;
use App\Match\Domain\FootballMatch;
use App\Match\Domain\MatchDateTime;
use App\Match\Domain\MatchId;
use App\Match\Domain\Repository\MatchRepositoryInterface;
use App\Team\Domain\TeamId;

final class ScheduleMatchHandler
{
    public function __construct(
        private readonly MatchRepositoryInterface $matches,
    ) {}

    public function handle(ScheduleMatchCommand $command): MatchId
    {
        $match = FootballMatch::schedule(
            externalId: $command->externalId,
            scheduledAt: MatchDateTime::fromDateAndTime($command->date, $command->time),
            competitionName: $command->competitionName,
            homeClub: new ClubInfo(
                $command->homeClubName,
                $command->homeClubLogoUrl,
                $command->homeTeamId !== null ? TeamId::fromString($command->homeTeamId) : null,
            ),
            awayClub: new ClubInfo(
                $command->awayClubName,
                $command->awayClubLogoUrl,
                $command->awayTeamId !== null ? TeamId::fromString($command->awayTeamId) : null,
            ),
        );

        $this->matches->save($match);

        return $match->getId();
    }
}
