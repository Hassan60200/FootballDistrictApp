<?php

declare(strict_types=1);

namespace App\Account\Infrastructure\Security;

use App\Account\Domain\HashedPassword;
use App\Account\Domain\PasswordHasherInterface;
use App\Account\Domain\PlainPassword;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

final readonly class SymfonyPasswordHasher implements PasswordHasherInterface
{
    public function __construct(private PasswordHasherFactoryInterface $hasherFactory)
    {
    }

    public function hash(PlainPassword $plainPassword): HashedPassword
    {
        $hasher = $this->hasherFactory->getPasswordHasher(PasswordAuthenticatedUserInterface::class);

        return HashedPassword::fromHash($hasher->hash($plainPassword->toString()));
    }
}
