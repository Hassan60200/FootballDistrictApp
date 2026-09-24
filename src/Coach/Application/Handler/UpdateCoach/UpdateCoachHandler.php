<?php

namespace App\Coach\Application\Handler\UpdateCoach;

use App\Coach\Domain\Email;
use App\Coach\Domain\Exception\CoachNotFoundException;
use App\Coach\Domain\Repository\CoachRepositoryInterface;

final class UpdateCoachHandler
{
    public function __construct(
        private readonly CoachRepositoryInterface $coaches,
    ) {}

    public function handle(UpdateCoachCommand $command): void
    {
        $coach = $this->coaches->findById($command->coachId);
        if ($coach === null) {
            throw CoachNotFoundException::withId($command->coachId);
        }

        $coach->rename($command->firstName, $command->lastName, Email::fromString($command->email));
        $this->coaches->save($coach);
    }
}
