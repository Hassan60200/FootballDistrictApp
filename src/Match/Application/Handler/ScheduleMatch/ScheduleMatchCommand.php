<?php

namespace App\Match\Application\Handler\ScheduleMatch;

final class ScheduleMatchCommand
{
    public function __construct(
        public readonly string $externalId,
        public readonly \DateTimeImmutable $date,
        public readonly string $time,
        public readonly string $competitionName,
        public readonly string $homeClubName,
        public readonly string $homeClubLogoUrl,
        public readonly ?string $homeTeamId,
        public readonly string $awayClubName,
        public readonly string $awayClubLogoUrl,
        public readonly ?string $awayTeamId,
    ) {}
}
