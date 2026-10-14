<?php

namespace App\Player\Domain\Exception;

final class InvalidPhoneNumberException extends \DomainException
{
    public static function withValue(string $value): self
    {
        return new self("\"{$value}\" is not a valid phone number");
    }
}
