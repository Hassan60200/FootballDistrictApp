<?php

namespace App\Admin\Application\Handler\ListAdmins;

use App\Admin\Domain\Admin;
use App\Admin\Domain\Repository\AdminRepositoryInterface;

final class ListAdminsHandler
{
    public function __construct(
        private readonly AdminRepositoryInterface $admins,
    )
    {
    }

    public function handle(ListAdminsQuery $query): array
    {
       return $this->admins->findAll();
    }
}
