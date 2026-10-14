<?php

declare(strict_types=1);

namespace App\Account\Domain;

use App\Account\Domain\Exception\WeakPasswordException;

final readonly class PlainPassword
{
    private const MIN_LENGTH = 8;

    private function __construct(
        #[\SensitiveParameter]
        private string $value,
    ) {
    }

    public static function fromString(#[\SensitiveParameter] string $password): self
    {
        if (mb_strlen($password) < self::MIN_LENGTH) {
            throw WeakPasswordException::tooShort(self::MIN_LENGTH);
        }

        return new self($password);
    }

    public function toString(): string
    {
        return $this->value;
    }
}
