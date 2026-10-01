<?php

namespace App\Match\Application\Handler\CancelMatch;

use App\Match\Domain\Event\MatchCancelledEvent;
use App\Match\Domain\Exception\MatchNotFoundException;
use App\Match\Domain\Repository\MatchRepositoryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

final class CancelMatchHandler
{
    public function __construct(
        private readonly MatchRepositoryInterface $matches,
        private readonly EventDispatcherInterface $eventDispatcher,
    ) {}

    public function handle(CancelMatchCommand $command): void
    {
        $match = $this->matches->findById($command->matchId);
        if ($match === null) {
            throw MatchNotFoundException::withId($command->matchId);
        }

        $match->cancel();
        $this->matches->save($match);

        $this->eventDispatcher->dispatch(new MatchCancelledEvent($match->getId()));
    }
}
