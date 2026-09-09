<?php

namespace App\Admin\Application\Handler\DeactivateAdmin;

use App\Admin\Domain\AdminId;

final class DeactivateAdminCommand
{
    public function __construct(
        public readonly AdminId $adminId,
    ) {}
}
