<?php

namespace App\Admin\Infrastructure\Doctrine;

use App\Admin\Domain\AdminId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

final class AdminIdType extends Type
{
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return 'VARCHAR(36)';
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?AdminId
    {
        return $value === null ? null : AdminId::fromString($value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        return $value === null ? null : $value->toString();
    }

    public function getName(): string
    {
        return 'admin_id';
    }
}
