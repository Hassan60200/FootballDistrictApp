<?php

declare(strict_types=1);

namespace App\Account\Domain\Exception;

final class InvalidEmailException extends \DomainException
{
    public static function withValue(string $value): self
    {
        return new self(sprintf('"%s" is not a valid email address.', $value));
    }
}
