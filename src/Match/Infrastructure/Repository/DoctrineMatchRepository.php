<?php

namespace App\Match\Infrastructure\Repository;

use App\Match\Domain\FootballMatch;
use App\Match\Domain\MatchId;
use App\Match\Domain\Repository\MatchRepositoryInterface;
use App\Team\Domain\TeamId;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class DoctrineMatchRepository extends ServiceEntityRepository implements MatchRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FootballMatch::class);
    }

    public function findById(MatchId $id): ?FootballMatch
    {
        return $this->find($id->toString());
    }

    public function save(FootballMatch $match): void
    {
        $this->getEntityManager()->persist($match);
        $this->getEntityManager()->flush();
    }

    public function findAll(): array
    {
        return parent::findAll();
    }

    public function findByTeam(TeamId $teamId): array
    {
        return $this->createQueryBuilder('m')
            ->where('m.homeClub.teamId = :teamId')
            ->orWhere('m.awayClub.teamId = :teamId')
            ->setParameter('teamId', $teamId->toString())
            ->getQuery()
            ->getResult();
    }

    public function findByWeek(\DateTimeImmutable $weekOf): array
    {
        $startOfWeek = $weekOf->modify('monday this week')->setTime(0, 0);
        $endOfWeek = $weekOf->modify('sunday this week')->setTime(23, 59, 59);

        return $this->createQueryBuilder('m')
            ->where('m.scheduledAt.value BETWEEN :start AND :end')
            ->setParameter('start', $startOfWeek)
            ->setParameter('end', $endOfWeek)
            ->getQuery()
            ->getResult();
    }
}
