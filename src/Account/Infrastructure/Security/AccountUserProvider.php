<?php

declare(strict_types=1);

namespace App\Account\Infrastructure\Security;

use App\Account\Domain\Email;
use App\Account\Domain\Repository\AccountRepositoryInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/** @implements UserProviderInterface<SecurityUser> */
final readonly class AccountUserProvider implements UserProviderInterface
{
    public function __construct(private AccountRepositoryInterface $accounts)
    {
    }

    public function loadUserByIdentifier(string $identifier): SecurityUser
    {
        try {
            $email = Email::fromString($identifier);
        } catch (\DomainException) {
            throw new UserNotFoundException();
        }

        $account = $this->accounts->findByEmail($email);

        if (null === $account) {
            throw new UserNotFoundException();
        }

        return SecurityUser::fromAccount($account);
    }

    public function refreshUser(UserInterface $user): SecurityUser
    {
        if (!$user instanceof SecurityUser) {
            throw new UnsupportedUserException(sprintf('Unsupported user class "%s".', $user::class));
        }

        return $this->loadUserByIdentifier($user->getUserIdentifier());
    }

    public function supportsClass(string $class): bool
    {
        return SecurityUser::class === $class || is_subclass_of($class, SecurityUser::class);
    }
}
