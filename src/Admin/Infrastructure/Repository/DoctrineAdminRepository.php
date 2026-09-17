<?php

namespace App\Admin\Infrastructure\Repository;

use App\Admin\Domain\Admin;
use App\Admin\Domain\AdminId;
use App\Admin\Domain\Repository\AdminRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

final class DoctrineAdminRepository extends ServiceEntityRepository implements AdminRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Admin::class);
    }

    public function findById(AdminId $id): ?Admin
    {
        return $this->find($id);
    }

    public function save(Admin $admin): void
    {
        $this->getEntityManager()->persist($admin);
        $this->getEntityManager()->flush();
    }

    public function findAll(): array
    {
        return parent::findAll();
    }
}
