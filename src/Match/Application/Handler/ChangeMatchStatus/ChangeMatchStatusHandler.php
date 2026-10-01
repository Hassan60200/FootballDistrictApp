<?php

namespace App\Match\Application\Handler\ChangeMatchStatus;

use App\Match\Domain\Event\MatchCancelledEvent;
use App\Match\Domain\Exception\MatchNotFoundException;
use App\Match\Domain\MatchStatus;
use App\Match\Domain\Repository\MatchRepositoryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

final class ChangeMatchStatusHandler
{
    public function __construct(
        private readonly MatchRepositoryInterface $matches,
        private readonly EventDispatcherInterface $eventDispatcher,
    ) {}

    public function handle(ChangeMatchStatusCommand $command): void
    {
        $match = $this->matches->findById($command->matchId);
        if ($match === null) {
            throw MatchNotFoundException::withId($command->matchId);
        }

        $match->changeStatus($command->status);
        $this->matches->save($match);

        if ($command->status === MatchStatus::CANCELLED) {
            $this->eventDispatcher->dispatch(new MatchCancelledEvent($match->getId()));
        }
    }
}
