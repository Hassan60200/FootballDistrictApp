<?php

declare(strict_types=1);

namespace App\Account\Application\Handler\RegisterAccount;

use App\Account\Application\RegisterAccount\RegisterAccountCommand;
use App\Account\Domain\Account;
use App\Account\Domain\AccountId;
use App\Account\Domain\Email;
use App\Account\Domain\Exception\AccountAlreadyExistsException;
use App\Account\Domain\Exception\ProfileNotFoundException;
use App\Account\Domain\PasswordHasherInterface;
use App\Account\Domain\PlainPassword;
use App\Account\Domain\ProfileDirectoryInterface;
use App\Account\Domain\ProfileReference;
use App\Account\Domain\Repository\AccountRepositoryInterface;

final readonly class RegisterAccountHandler
{
    public function __construct(
        private AccountRepositoryInterface $accounts,
        private PasswordHasherInterface $passwordHasher,
        private ProfileDirectoryInterface $profileDirectory,
    ) {
    }

    public function __invoke(RegisterAccountCommand $command): AccountId
    {
        $email = Email::fromString($command->email);
        $password = PlainPassword::fromString($command->password);
        $profile = ProfileReference::of($command->profileType, $command->profileIdentifier);

        if (!$this->profileDirectory->exists($profile)) {
            throw ProfileNotFoundException::forProfile($profile);
        }

        if (null !== $this->accounts->findByEmail($email)) {
            throw AccountAlreadyExistsException::withEmail($email);
        }

        if (null !== $this->accounts->findByProfile($profile)) {
            throw AccountAlreadyExistsException::forProfile($profile);
        }

        $account = Account::open($email, $this->passwordHasher->hash($password), $profile);
        $this->accounts->save($account);

        return $account->id();
    }
}
