<?php

declare(strict_types=1);

namespace App\Account\Domain;

final readonly class HashedPassword
{
    private function __construct(
        private string $value,
    ) {
    }

    public static function fromHash(string $hash): self
    {
        return new self($hash);
    }

    public function toString(): string
    {
        return $this->value;
    }
}
