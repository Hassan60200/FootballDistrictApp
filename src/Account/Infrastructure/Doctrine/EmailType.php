<?php

declare(strict_types=1);

namespace App\Account\Infrastructure\Doctrine;

use App\Account\Domain\Email;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

final class EmailType extends Type
{
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getStringTypeDeclarationSQL(['length' => 180]);
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?Email
    {
        if (null === $value || $value instanceof Email) {
            return $value;
        }

        return Email::fromString((string) $value);
    }

    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): ?string
    {
        if (null === $value) {
            return null;
        }

        if (!$value instanceof Email) {
            throw new \InvalidArgumentException('Expected an Email instance.');
        }

        return $value->toString();
    }
}
