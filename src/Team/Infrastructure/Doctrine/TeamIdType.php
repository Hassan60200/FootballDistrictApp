<?php

namespace App\Team\Infrastructure\Doctrine;

use App\Team\Domain\TeamId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

final class TeamIdType extends Type
{
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return 'VARCHAR(36)';
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?TeamId
    {
        return $value === null ? null : TeamId::fromString($value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        return $value === null ? null : $value->toString();
    }

    public function getName(): string
    {
        return 'team_id';
    }
}
