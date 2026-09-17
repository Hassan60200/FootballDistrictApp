<?php

namespace App\Admin\Infrastructure\Controller\Request;

use Symfony\Component\Validator\Constraints as Assert;

final class PromoteAdminRequest
{
    public function __construct(
        #[Assert\Count(min: 1)]
        #[Assert\All([new Assert\NotBlank()])]
        public readonly array $roles,
        #[Assert\NotBlank]
        public readonly ?string $firstName = null,
        #[Assert\NotBlank]
        public readonly ?string $lastName = null,
        #[Assert\NotBlank]
        #[Assert\Email]
        public readonly ?string $email = null,
    ) {}
}
