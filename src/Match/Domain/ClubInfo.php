<?php

namespace App\Match\Domain;

final class ClubInfo
{
    public function __construct(
        private readonly string $name,
        private readonly string $logoUrl,
    ) {}

    public function getName(): string { return $this->name; }
    public function getLogoUrl(): string { return $this->logoUrl; }
}
