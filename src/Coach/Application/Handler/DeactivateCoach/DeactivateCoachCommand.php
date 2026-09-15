<?php

namespace App\Coach\Application\Handler\DeactivateCoach;

use App\Coach\Domain\CoachId;

final class DeactivateCoachCommand
{
    public function __construct(
        public readonly CoachId $coachId,
    ) {}
}
