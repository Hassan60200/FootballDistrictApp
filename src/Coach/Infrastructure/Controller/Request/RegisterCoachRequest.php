<?php

namespace App\Coach\Infrastructure\Controller\Request;

use Symfony\Component\Validator\Constraints as Assert;

final class RegisterCoachRequest
{
    public function __construct(
        #[Assert\NotBlank]
        public readonly string $firstName,

        #[Assert\NotBlank]
        public readonly string $lastName,

        #[Assert\NotBlank]
        #[Assert\Email]
        public readonly string $email,
    ) {}
}
