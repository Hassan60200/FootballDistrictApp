<?php

namespace App\Admin\Application\Handler\PromoteUserToAdmin;

final class PromoteUserToAdminCommand
{
    public function __construct(
        public readonly string $firstName,
        public readonly string $lastName,
        public readonly string $email,
        public readonly array  $roles
    )
    {
    }
}
