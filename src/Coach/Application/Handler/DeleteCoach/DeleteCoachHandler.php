<?php

namespace App\Coach\Application\Handler\DeleteCoach;

use App\Coach\Domain\Exception\CoachNotFoundException;
use App\Coach\Domain\Repository\CoachRepositoryInterface;

final class DeleteCoachHandler
{
    public function __construct(
        private readonly CoachRepositoryInterface $coaches,
    ) {}

    public function handle(DeleteCoachCommand $command): void
    {
        $coach = $this->coaches->findById($command->coachId);
        if ($coach === null) {
            throw CoachNotFoundException::withId($command->coachId);
        }

        $this->coaches->delete($coach);
    }
}
