<?php

namespace App\Match\Infrastructure\Controller\Request;

use Symfony\Component\Validator\Constraints as Assert;

final class ClubInfoRequest
{
    public function __construct(
        #[Assert\NotBlank]
        public readonly string $name,

        #[Assert\NotBlank]
        public readonly string $logoUrl,

        public readonly ?string $teamId = null,
    ) {}
}
