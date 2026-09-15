<?php

namespace App\Coach\Application\Handler\DeactivateCoach;

use App\Coach\Domain\Exception\CoachNotFoundException;
use App\Coach\Domain\Repository\CoachRepositoryInterface;

final class DeactivateCoachHandler
{
    public function __construct(
        private readonly CoachRepositoryInterface $coaches,
    ) {}

    public function handle(DeactivateCoachCommand $command): void
    {
        $coach = $this->coaches->findById($command->coachId);

        if ($coach === null) {
            throw CoachNotFoundException::withId($command->coachId);
        }

        $coach->deactivate();
        $this->coaches->save($coach);
    }
}
