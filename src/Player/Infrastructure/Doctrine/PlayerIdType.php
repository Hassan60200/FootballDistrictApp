<?php

namespace App\Player\Infrastructure\Doctrine;

use App\Player\Domain\PlayerId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

final class PlayerIdType extends Type
{
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return 'VARCHAR(36)';
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?PlayerId
    {
        return $value === null ? null : PlayerId::fromString($value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        return $value === null ? null : $value->toString();
    }

    public function getName(): string
    {
        return 'player_id';
    }
}
