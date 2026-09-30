<?php

namespace App\Coach\Application\Handler\DeleteCoach;

use App\Coach\Domain\CoachId;

final class DeleteCoachCommand
{
    public function __construct(
        public readonly CoachId $coachId,
    ) {}
}
