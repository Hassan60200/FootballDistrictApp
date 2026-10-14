<?php

declare(strict_types=1);

namespace App\Account\Domain;

use App\Account\Domain\Exception\AccountAlreadyDeactivatedException;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'accounts')]
#[ORM\UniqueConstraint(name: 'uniq_account_profile', columns: ['profile_type', 'profile_identifier'])]
final class Account
{
    #[ORM\Id]
    #[ORM\Column(type: 'account_id')]
    private AccountId $id;

    #[ORM\Column(type: 'account_email', unique: true)]
    private Email $email;

    #[ORM\Column(type: 'account_password_hash')]
    private HashedPassword $password;

    #[ORM\Embedded(class: ProfileReference::class, columnPrefix: false)]
    private ProfileReference $profile;

    #[ORM\Column(type: 'boolean')]
    private bool $active = true;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    private function __construct(
        AccountId $id,
        Email $email,
        HashedPassword $password,
        ProfileReference $profile,
    ) {
        $this->id = $id;
        $this->email = $email;
        $this->password = $password;
        $this->profile = $profile;
        $this->createdAt = new \DateTimeImmutable();
    }

    public static function open(Email $email, HashedPassword $password, ProfileReference $profile): self
    {
        return new self(AccountId::generate(), $email, $password, $profile);
    }

    public function deactivate(): void
    {
        if (!$this->active) {
            throw AccountAlreadyDeactivatedException::withId($this->id);
        }

        $this->active = false;
    }

    public function id(): AccountId
    {
        return $this->id;
    }

    public function email(): Email
    {
        return $this->email;
    }

    public function password(): HashedPassword
    {
        return $this->password;
    }

    public function profile(): ProfileReference
    {
        return $this->profile;
    }

    public function role(): string
    {
        return match ($this->profile->type()) {
            ProfileType::ADMIN => 'ROLE_ADMIN',
            ProfileType::COACH => 'ROLE_COACH',
            ProfileType::PLAYER => 'ROLE_PLAYER',
        };
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function createdAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
}
