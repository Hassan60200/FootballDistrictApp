<?php

namespace App\Admin\Domain\Repository;

use App\Admin\Domain\Admin;
use App\Admin\Domain\AdminId;

interface AdminRepositoryInterface
{
    public function findById(AdminId $id): ?Admin;
    public function save(Admin $admin): void;
    public function findAll(): array;
}
