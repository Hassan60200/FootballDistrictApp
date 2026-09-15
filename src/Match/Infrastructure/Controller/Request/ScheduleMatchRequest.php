<?php

namespace App\Match\Infrastructure\Controller\Request;

use Symfony\Component\Validator\Constraints as Assert;

final class ScheduleMatchRequest
{
    public function __construct(
        #[Assert\NotBlank]
        public readonly string $externalId,

        public readonly \DateTimeImmutable $date,

        #[Assert\NotBlank]
        #[Assert\Regex('/^\d{1,2}H\d{2}$/')]
        public readonly string $time,

        #[Assert\NotBlank]
        public readonly string $competitionName,

        #[Assert\Valid]
        public readonly ClubInfoRequest $homeClub,

        #[Assert\Valid]
        public readonly ClubInfoRequest $awayClub,
    ) {}
}
