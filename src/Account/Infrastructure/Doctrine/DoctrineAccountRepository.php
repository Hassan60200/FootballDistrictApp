<?php

declare(strict_types=1);

namespace App\Account\Infrastructure\Doctrine;

use App\Account\Domain\Account;
use App\Account\Domain\AccountId;
use App\Account\Domain\AccountRepositoryInterface;
use App\Account\Domain\Email;
use App\Account\Domain\ProfileReference;
use Doctrine\ORM\EntityManagerInterface;

final readonly class DoctrineAccountRepository implements AccountRepositoryInterface
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function findById(AccountId $id): ?Account
    {
        return $this->entityManager->find(Account::class, $id);
    }

    public function findByEmail(Email $email): ?Account
    {
        return $this->entityManager->getRepository(Account::class)->findOneBy(['email' => $email]);
    }

    public function findByProfile(ProfileReference $profile): ?Account
    {
        return $this->entityManager->getRepository(Account::class)->findOneBy([
            'profile.type' => $profile->type(),
            'profile.identifier' => $profile->identifier(),
        ]);
    }

    public function save(Account $account): void
    {
        $this->entityManager->persist($account);
        $this->entityManager->flush();
    }
}
