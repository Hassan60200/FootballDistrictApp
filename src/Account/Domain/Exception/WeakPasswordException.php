<?php

declare(strict_types=1);

namespace App\Account\Domain\Exception;

final class WeakPasswordException extends \DomainException
{
    public static function tooShort(int $minLength): self
    {
        return new self(sprintf('Password must contain at least %d characters.', $minLength));
    }
}
