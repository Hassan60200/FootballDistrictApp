<?php

namespace App\Match\Application\Handler\UpdateMatch;

use App\Match\Domain\Exception\MatchNotFoundException;
use App\Match\Domain\MatchDateTime;
use App\Match\Domain\Repository\MatchRepositoryInterface;

final class UpdateMatchHandler
{
    public function __construct(
        private readonly MatchRepositoryInterface $matches,
    ) {}

    public function handle(UpdateMatchCommand $command): void
    {
        $match = $this->matches->findById($command->matchId);
        if ($match === null) {
            throw MatchNotFoundException::withId($command->matchId);
        }

        $match->reschedule(
            MatchDateTime::fromDateAndTime($command->date, $command->time),
            $command->competitionName,
            $command->awayClubName,
        );
        $this->matches->save($match);
    }
}
