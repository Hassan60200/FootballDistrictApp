<?php

namespace App\Match\Domain;

use App\Team\Domain\TeamId;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
final class ClubInfo
{
    public function __construct(
        #[ORM\Column(type: 'string')]
        private readonly string $name,

        #[ORM\Column(type: 'string')]
        private readonly string $logoUrl,

        #[ORM\Column(type: 'team_id', nullable: true)]
        private readonly ?TeamId $teamId = null,
    ) {}

    public function getName(): string { return $this->name; }
    public function getLogoUrl(): string { return $this->logoUrl; }
    public function getTeamId(): ?TeamId { return $this->teamId; }
}
