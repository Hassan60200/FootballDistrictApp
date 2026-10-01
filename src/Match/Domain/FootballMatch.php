<?php

namespace App\Match\Domain;

use App\Match\Domain\Exception\CannotSummonForPastMatchException;
use App\Match\Domain\Exception\MatchAlreadyCancelledException;
use App\Match\Domain\Exception\MaxSummonedPlayersReachedException;
use App\Match\Domain\Exception\PlayerAlreadySummonedException;
use App\Player\Domain\PlayerId;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'matches')]
final class FootballMatch
{
    private const MAX_SUMMONED_PLAYERS = 14;

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

    /** @var PlayerId[] */
    #[ORM\Column(type: 'player_id_collection')]
    private array $summonedPlayers = [];

    #[ORM\Column(type: 'string', enumType: MatchStatus::class)]
    private MatchStatus $status;

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
        $this->status = MatchStatus::SCHEDULED;
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
    public function getStatus(): MatchStatus { return $this->status; }

    public function summonPlayer(PlayerId $playerId): void
    {
        if ($this->hasAlreadyBeenPlayed()) {
            throw CannotSummonForPastMatchException::forMatch($this->id);
        }

        if ($this->isPlayerAlreadySummoned($playerId)) {
            throw PlayerAlreadySummonedException::forMatch($this->id, $playerId);
        }

        if (count($this->summonedPlayers) >= self::MAX_SUMMONED_PLAYERS) {
            throw MaxSummonedPlayersReachedException::forMatch($this->id);
        }

        $this->summonedPlayers[] = $playerId;
    }

    public function hasEnoughSummonedPlayers(): bool
    {
        return count($this->summonedPlayers) >= 11;
    }

    public function isPlayerAlreadySummoned(PlayerId $playerId): bool
    {
        foreach ($this->summonedPlayers as $summoned) {
            if ($summoned->equals($playerId)) {
                return true;
            }
        }
        return false;
    }

    private function hasAlreadyBeenPlayed(): bool
    {
        return $this->scheduledAt->toDateTimeImmutable() < new \DateTimeImmutable();
    }

    public function getSummonedPlayers(): array
    {
        return $this->summonedPlayers;
    }

    public function cancel(): void
    {
        if ($this->status === MatchStatus::CANCELLED) {
            throw MatchAlreadyCancelledException::forMatch($this->id);
        }

        $this->status = MatchStatus::CANCELLED;
    }
}
