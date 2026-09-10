<?php

namespace App\Player\Domain;

use App\Player\Domain\Exception\InvalidEmailException;

final class Email
{
    private function __construct(
        private readonly string $value,
    ) {
    }

    public static function fromString(string $email): self
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw InvalidEmailException::withValue($email);
        }

        return new self($email);
    }

    public function toString(): string
    {
        return $this->value;
    }

    public function equals(Email $other): bool
    {
        return $this->value === $other->value;
    }
}
