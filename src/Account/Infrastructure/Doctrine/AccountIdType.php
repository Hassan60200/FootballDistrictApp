<?php

declare(strict_types=1);

namespace App\Account\Infrastructure\Doctrine;

use App\Account\Domain\AccountId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

final class AccountIdType extends Type
{
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getStringTypeDeclarationSQL(['length' => 36, 'fixed' => true]);
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?AccountId
    {
        if (null === $value || $value instanceof AccountId) {
            return $value;
        }

        return AccountId::fromString((string) $value);
    }

    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): ?string
    {
        if (null === $value) {
            return null;
        }

        if (!$value instanceof AccountId) {
            throw new \InvalidArgumentException('Expected an AccountId instance.');
        }

        return $value->toString();
    }
}
