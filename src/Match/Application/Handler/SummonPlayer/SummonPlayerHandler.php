<?php

namespace App\Match\Application\Handler\SummonPlayer;

use App\Match\Domain\Event\PlayerSummonedEvent;
use App\Match\Domain\Exception\MatchNotFoundException;
use App\Match\Domain\PlayerWeeklyAvailabilityChecker;
use App\Match\Domain\Repository\MatchRepositoryInterface;
use App\Match\Domain\Exception\PlayerAlreadySummonedThisWeekException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

final class SummonPlayerHandler
{
    public function __construct(
        private readonly MatchRepositoryInterface $matches,
        private readonly PlayerWeeklyAvailabilityChecker $availabilityChecker,
        private readonly EventDispatcherInterface $eventDispatcher,
    ) {}

    public function handle(SummonPlayerCommand $command): void
    {
        $match = $this->matches->findById($command->matchId);
        if ($match === null) {
            throw MatchNotFoundException::withId($command->matchId);
        }

        if (!$this->availabilityChecker->isAvailableForWeek(
            $command->playerId,
            $match->getScheduledAt()->toDateTimeImmutable()
        )) {
            throw PlayerAlreadySummonedThisWeekException::forPlayer($command->playerId);
        }

        $match->summonPlayer($command->playerId);
        $this->matches->save($match);

        $this->eventDispatcher->dispatch(
            new PlayerSummonedEvent($match->getId(), $command->playerId)
        );
    }
}
