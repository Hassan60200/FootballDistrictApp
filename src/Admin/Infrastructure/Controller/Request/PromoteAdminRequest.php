<?php

namespace App\Admin\Infrastructure\Controller\Request;

use Symfony\Component\Validator\Constraints as Assert;

final class PromoteAdminRequest
{
    public function __construct(
        #[Assert\NotBlank]
        public readonly string $roles,
    ) {}
}
