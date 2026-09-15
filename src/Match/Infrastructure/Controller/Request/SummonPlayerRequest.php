<?php

namespace App\Match\Infrastructure\Controller\Request;

final class SummonPlayerRequest
{
    public function __construct(
        public readonly string $playerId,
    ) {}
}
