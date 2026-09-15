<?php

namespace App\Coach\Application\Handler\ListCoaches;

use App\Coach\Domain\Repository\CoachRepositoryInterface;

final class ListCoachesHandler
{
    public function __construct(
        private readonly CoachRepositoryInterface $coaches,
    ) {}

    public function handle(ListCoachesQuery $query): array
    {
        return $this->coaches->findAll();
    }
}
