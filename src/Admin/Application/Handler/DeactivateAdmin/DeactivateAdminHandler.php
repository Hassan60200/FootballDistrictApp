<?php

namespace App\Admin\Application\Handler\DeactivateAdmin;

use App\Admin\Domain\Exception\AdminNotFoundException;
use App\Admin\Domain\Repository\AdminRepositoryInterface;

final class DeactivateAdminHandler
{
    public function __construct(
        private readonly AdminRepositoryInterface $admins,
    ) {}

    public function handle(DeactivateAdminCommand $command): void
    {
        $admin = $this->admins->findById($command->adminId);

        if ($admin === null) {
            throw AdminNotFoundException::withId($command->adminId);
        }

        $admin->deactivate();
        $this->admins->save($admin);
    }
}
