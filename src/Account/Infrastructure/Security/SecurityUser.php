<?php

declare(strict_types=1);

namespace App\Account\Infrastructure\Security;

use App\Account\Domain\Account;
use App\Account\Domain\ProfileType;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Adaptateur : traduit l'agrégat Account vers ce que Symfony Security attend.
 * Le Domain ne connaît pas cette classe.
 */
final readonly class SecurityUser implements UserInterface, PasswordAuthenticatedUserInterface
{
    private function __construct(
        private string $accountId,
        private string $email,
        private string $passwordHash,
        private string $role,
        private ProfileType $profileType,
        private string $profileIdentifier,
        private bool $active,
    ) {
    }

    public static function fromAccount(Account $account): self
    {
        return new self(
            $account->id()->toString(),
            $account->email()->toString(),
            $account->password()->toString(),
            $account->role(),
            $account->profile()->type(),
            $account->profile()->identifier(),
            $account->isActive(),
        );
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    /** @return list<string> */
    public function getRoles(): array
    {
        return [$this->role];
    }

    public function getPassword(): string
    {
        return $this->passwordHash;
    }

    public function accountId(): string
    {
        return $this->accountId;
    }

    public function profileType(): ProfileType
    {
        return $this->profileType;
    }

    public function profileIdentifier(): string
    {
        return $this->profileIdentifier;
    }

    public function isActive(): bool
    {
        return $this->active;
    }
}
