<?php

namespace App\Player\Domain;

use App\Player\Domain\Exception\InvalidPhoneNumberException;

final class PhoneNumber
{
    private function __construct(
        private readonly string $value,
    ) {}

    public static function fromString(string $phoneNumber): self
    {
        $normalized = preg_replace('/[\s.\-()]/', '', $phoneNumber);

        // Format français local (06...) -> international (+336...)
        if (preg_match('/^0[1-9]\d{8}$/', $normalized)) {
            $normalized = '+33' . substr($normalized, 1);
        }

        if (!preg_match('/^\+[1-9]\d{7,14}$/', $normalized)) {
            throw InvalidPhoneNumberException::withValue($phoneNumber);
        }

        return new self($normalized);
    }

    public function toString(): string
    {
        return $this->value;
    }

    public function equals(PhoneNumber $other): bool
    {
        return $this->value === $other->value;
    }
}
