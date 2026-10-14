<?php

declare(strict_types=1);

namespace App\Account\Domain;

use App\Account\Domain\Exception\InvalidEmailException;

final readonly class Email
{
    private function __construct(private string $value)
    {
    }

    public static function fromString(string $value): self
    {
        $normalized = mb_strtolower(trim($value));

        if (false === filter_var($normalized, FILTER_VALIDATE_EMAIL)) {
            throw InvalidEmailException::withValue($value);
        }

        return new self($normalized);
    }

    public function toString(): string
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
