<?php

namespace App\Match\Domain;

use Symfony\Component\Uid\Uuid;

final class MatchId
{
    private function __construct(
        private readonly Uuid $value,
    ) {
    }

    public static function generate(): self
    {
        return new self(Uuid::v4());
    }

    public static function fromString(string $id): self
    {
        return new self(Uuid::fromString($id));
    }

    public function equals(MatchId $other): bool
    {
        return $this->value->equals($other->value);
    }

    public function toString(): string
    {
        return (string) $this->value;
    }
}
