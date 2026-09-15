<?php

namespace App\Team\Infrastructure\Controller\Request;

use App\Team\Domain\TeamCategory;
use Symfony\Component\Validator\Constraints as Assert;

final class CreateTeamRequest
{
    public function __construct(
        #[Assert\NotBlank]
        public readonly string $name,

        public readonly TeamCategory $category,
    ) {}
}
