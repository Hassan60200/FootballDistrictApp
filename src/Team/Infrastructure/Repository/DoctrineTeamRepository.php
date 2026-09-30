<?php

namespace App\Team\Infrastructure\Repository;

use App\Team\Domain\Team;
use App\Team\Domain\TeamId;
use App\Team\Domain\Repository\TeamRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class DoctrineTeamRepository extends ServiceEntityRepository implements TeamRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Team::class);
    }

    public function findById(TeamId $id): ?Team
    {
        return $this->find($id);
    }

    public function save(Team $team): void
    {
        $this->getEntityManager()->persist($team);
        $this->getEntityManager()->flush();
    }

    public function findAll(): array
    {
        return parent::findAll();
    }

    public function delete(Team $team): void
    {
        $this->getEntityManager()->remove($team);
        $this->getEntityManager()->flush();
    }
}
