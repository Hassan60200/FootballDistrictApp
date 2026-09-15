<?php

namespace App\Coach\Application\Handler\RegisterCoach;

use App\Coach\Domain\Coach;
use App\Coach\Domain\CoachId;
use App\Coach\Domain\Email;
use App\Coach\Domain\Repository\CoachRepositoryInterface;

final class RegisterCoachHandler
{
    public function __construct(
        private readonly CoachRepositoryInterface $coaches,
    ) {}

    public function handle(RegisterCoachCommand $command): CoachId
    {
        $coach = Coach::register(
            firstName: $command->firstName,
            lastName: $command->lastName,
            email: Email::fromString($command->email),
        );

        $this->coaches->save($coach);

        return $coach->getId();
    }
}
