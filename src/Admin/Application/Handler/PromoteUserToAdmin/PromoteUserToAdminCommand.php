<?php

namespace App\Admin\Application\Handler\PromoteUserToAdmin;

final class PromoteUserToAdminCommand
{
    public function __construct(
        public readonly string $roles,
    ) {}
}
