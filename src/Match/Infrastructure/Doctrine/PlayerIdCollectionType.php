<?php

namespace App\Match\Infrastructure\Doctrine;

use App\Player\Domain\PlayerId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

final class PlayerIdCollectionType extends Type
{
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return 'JSON';
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): array
    {
        if ($value === null) {
            return [];
        }

        $ids = is_string($value) ? json_decode($value, true) : $value;

        return array_map(
            static fn (string $id) => PlayerId::fromString($id),
            $ids,
        );
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if ($value === null) {
            return null;
        }

        return json_encode(array_map(
            static fn (PlayerId $id) => $id->toString(),
            $value,
        ));
    }

    public function getName(): string
    {
        return 'player_id_collection';
    }
}
