<?php

namespace App\Coach\Application\Handler\UpdateCoach;

use App\Coach\Domain\CoachId;

final class UpdateCoachCommand
{
    public function __construct(
        public readonly CoachId $coachId,
        public readonly string $firstName,
        public readonly string $lastName,
        public readonly string $email,
    ) {}
}
