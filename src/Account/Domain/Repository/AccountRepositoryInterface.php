<?php

declare(strict_types=1);

namespace App\Account\Domain\Repository;

use App\Account\Domain\Account;
use App\Account\Domain\ProfileReference;
use App\Account\Domain\AccountId;
use App\Account\Domain\Email;

interface AccountRepositoryInterface
{
    public function findById(AccountId $id): ?Account;
    public function findByEmail(Email $email): ?Account;
    public function findByProfile(ProfileReference $profile): ?Account;
    public function save(Account $account): void;
}
