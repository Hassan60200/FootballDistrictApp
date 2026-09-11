<?php

namespace App\Player\Infrastructure\Controller\Request;

use App\Player\Domain\Position;
use Symfony\Component\Validator\Constraints as Assert;

final class RegisterPlayerRequest
{
    public function __construct(
        #[Assert\NotBlank]
        public readonly string $firstName,

        #[Assert\NotBlank]
        public readonly string $lastName,

        #[Assert\Positive]
        public readonly int $age,

        #[Assert\NotBlank]
        #[Assert\Email]
        public readonly string $email,

        public readonly Position $position,
    ) {}
}
