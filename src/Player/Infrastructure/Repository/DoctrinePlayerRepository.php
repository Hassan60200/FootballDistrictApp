<?php

namespace App\Player\Infrastructure\Repository;

use App\Player\Domain\Player;
use App\Player\Domain\PlayerId;
use App\Player\Domain\Repository\PlayerRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class DoctrinePlayerRepository extends ServiceEntityRepository implements PlayerRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Player::class);
    }

    public function findById(PlayerId $id): ?Player
    {
        return $this->find($id);
    }

    public function save(Player $player): void
    {
        $this->getEntityManager()->persist($player);
        $this->getEntityManager()->flush();
    }

    public function findAll(): array
    {
        return parent::findAll();
    }

    public function delete(Player $player): void
    {
        $this->getEntityManager()->remove($player);
        $this->getEntityManager()->flush();
    }
}
