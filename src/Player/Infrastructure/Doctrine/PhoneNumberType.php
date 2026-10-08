<?php

namespace App\Player\Infrastructure\Doctrine;

use App\Player\Domain\PhoneNumber;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

final class PhoneNumberType extends Type
{
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return 'VARCHAR(20)';
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?PhoneNumber
    {
        return $value === null ? null : PhoneNumber::fromString($value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        return $value === null ? null : $value->toString();
    }

    public function getName(): string
    {
        return 'player_phone_number';
    }
}
