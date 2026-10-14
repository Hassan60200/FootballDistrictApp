<?php

declare(strict_types=1);

namespace App\Account\Infrastructure\Doctrine;

use App\Account\Domain\HashedPassword;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

final class HashedPasswordType extends Type
{
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getStringTypeDeclarationSQL(['length' => 255]);
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?HashedPassword
    {
        if (null === $value || $value instanceof HashedPassword) {
            return $value;
        }

        return HashedPassword::fromHash((string) $value);
    }

    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): ?string
    {
        if (null === $value) {
            return null;
        }

        if (!$value instanceof HashedPassword) {
            throw new \InvalidArgumentException('Expected a HashedPassword instance.');
        }

        return $value->toString();
    }
}
