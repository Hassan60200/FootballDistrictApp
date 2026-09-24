<?php

namespace App\Coach\Infrastructure\Repository;

use App\Coach\Domain\Coach;
use App\Coach\Domain\CoachId;
use App\Coach\Domain\Repository\CoachRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class DoctrineCoachRepository extends ServiceEntityRepository implements CoachRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Coach::class);
    }

    public function findById(CoachId $id): ?Coach
    {
        return $this->find($id);
    }

    public function save(Coach $coach): void
    {
        $this->getEntityManager()->persist($coach);
        $this->getEntityManager()->flush();
    }

    public function findAll(): array
    {
        return parent::findAll();
    }

    public function delete(Coach $coach): void
    {
        $this->getEntityManager()->remove($coach);
        $this->getEntityManager()->flush();
    }
}
