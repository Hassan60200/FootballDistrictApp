<?php

declare(strict_types=1);

namespace App\Account\Infrastructure\Security;

use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/** Refuse la connexion d'un compte désactivé. */
final class AccountUserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user): void
    {
        if ($user instanceof SecurityUser && !$user->isActive()) {
            throw new CustomUserMessageAccountStatusException('This account is deactivated.');
        }
    }

    public function checkPostAuth(UserInterface $user, ?TokenInterface $token = null): void
    {
    }
}
