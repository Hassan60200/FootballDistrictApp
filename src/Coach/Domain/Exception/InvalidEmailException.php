<?php

namespace App\Coach\Domain\Exception;

final class InvalidEmailException extends \DomainException
{
    public static function withValue(string $value): self
    {
        return new self("\"{$value}\" is not a valid email address");
    }
}
