<?php

namespace App\Match\Domain;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'matches')]
final class FootballMatch
{
    #[ORM\Id]
    #[ORM\Column(type: 'match_id', unique: true)]
    private readonly MatchId $id;

    #[ORM\Column(type: 'string')]
    private readonly string $externalId;

    #[ORM\Embedded(class: MatchDateTime::class)]
    private readonly MatchDateTime $scheduledAt;

    #[ORM\Column(type: 'string')]
    private readonly string $competitionName;

    #[ORM\Embedded(class: ClubInfo::class, columnPrefix: 'home_')]
    private readonly ClubInfo $homeClub;

    #[ORM\Embedded(class: ClubInfo::class, columnPrefix: 'away_')]
    private readonly ClubInfo $awayClub;

    private function __construct(
        MatchId $id,
        string $externalId,
        MatchDateTime $scheduledAt,
        string $competitionName,
        ClubInfo $homeClub,
        ClubInfo $awayClub,
    ) {
        $this->id = $id;
        $this->externalId = $externalId;
        $this->scheduledAt = $scheduledAt;
        $this->competitionName = $competitionName;
        $this->homeClub = $homeClub;
        $this->awayClub = $awayClub;
    }

    public static function schedule(
        string $externalId,
        MatchDateTime $scheduledAt,
        string $competitionName,
        ClubInfo $homeClub,
        ClubInfo $awayClub,
    ): self {
        return new self(MatchId::generate(), $externalId, $scheduledAt, $competitionName, $homeClub, $awayClub);
    }

    public function getId(): MatchId { return $this->id; }
    public function getScheduledAt(): MatchDateTime { return $this->scheduledAt; }
    public function getCompetitionName(): string { return $this->competitionName; }
    public function getHomeClub(): ClubInfo { return $this->homeClub; }
    public function getAwayClub(): ClubInfo { return $this->awayClub; }
}
