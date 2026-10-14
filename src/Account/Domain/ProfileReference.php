<?php

declare(strict_types=1);

namespace App\Account\Domain;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
final class ProfileReference
{
    #[ORM\Column(name: 'profile_type', type: 'string', length: 20, enumType: ProfileType::class)]
    private ProfileType $type;

    #[ORM\Column(name: 'profile_identifier', type: 'string', length: 36)]
    private string $identifier;

    private function __construct(ProfileType $type, string $identifier)
    {
        $this->type = $type;
        $this->identifier = $identifier;
    }

    public static function of(ProfileType $type, string $identifier): self
    {
        return new self($type, $identifier);
    }

    public function type(): ProfileType
    {
        return $this->type;
    }

    public function identifier(): string
    {
        return $this->identifier;
    }
}
