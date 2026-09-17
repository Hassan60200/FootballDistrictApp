<?php

namespace App\Admin\Application\Handler\PromoteUserToAdmin;

use App\Admin\Domain\Admin;
use App\Admin\Domain\AdminId;
use App\Admin\Domain\Email\Email;
use App\Admin\Domain\Repository\AdminRepositoryInterface;

final class PromoteUserToAdminHandler
{
    public function __construct(
        private readonly AdminRepositoryInterface $admins,
    ) {}

    public function handle(PromoteUserToAdminCommand $command): AdminId
    {
        $admin = Admin::promote(firstName: $command->firstName,
            lastName: $command->lastName,
            email: Email::fromString($command->email),
            roles: $command->roles,);
        $this->admins->save($admin);

        return $admin->getAdminId();
    }
}
