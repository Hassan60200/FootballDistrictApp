<?php

namespace App\Match\Infrastructure\Doctrine;

use App\Match\Domain\MatchId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

final class MatchIdType extends Type
{
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return 'VARCHAR(36)';
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?MatchId
    {
        return $value === null ? null : MatchId::fromString($value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        return $value === null ? null : $value->toString();
    }

    public function getName(): string
    {
        return 'match_id';
    }
}
