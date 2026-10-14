<?php

declare(strict_types=1);

namespace App\Account\Domain;

use Symfony\Component\Uid\Uuid;

final readonly class AccountId
{
    private function __construct(
        private Uuid $value,
    ) {
    }

    public static function generate(): self
    {
        return new self(Uuid::v7());
    }

    public static function fromString(string $id): self
    {
        return new self(Uuid::fromString($id));
    }

    public function equals(self $other): bool
    {
        return $this->value->equals($other->value);
    }

    public function toString(): string
    {
        return $this->value->toRfc4122();
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}
