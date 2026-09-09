<?php

namespace App\Admin\Application\Handler\PromoteUserToAdmin;

use App\Admin\Domain\Admin;
use App\Admin\Domain\AdminId;
use App\Admin\Domain\Repository\AdminRepositoryInterface;

final class PromoteUserToAdminHandler
{
    public function __construct(
        private readonly AdminRepositoryInterface $admins,
    ) {}

    public function handle(PromoteUserToAdminCommand $command): AdminId
    {
        $admin = Admin::promote($command->roles);
        $this->admins->save($admin);

        return $admin->getAdminId();
    }
}
