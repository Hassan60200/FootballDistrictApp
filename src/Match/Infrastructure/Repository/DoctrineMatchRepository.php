<?php

namespace App\Match\Infrastructure\Repository;

use App\Match\Domain\FootballMatch;
use App\Match\Domain\MatchId;
use App\Match\Domain\Repository\MatchRepositoryInterface;
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
}
