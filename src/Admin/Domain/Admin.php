<?php

namespace App\Admin\Domain;

use App\Admin\Domain\Email\Email;
use App\Admin\Domain\Exception\AdminAlreadyDeactivatedException;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'admins')]
final class Admin
{
    #[ORM\Id]
    #[ORM\Column(type: 'admin_id', unique: true)]
    private readonly AdminId $adminId;

    #[ORM\Column(type: 'string')]
    private readonly string $firstName;

    #[ORM\Column(type: 'string')]
    private readonly string $lastName;

    #[ORM\Column(type: 'admin_email', unique: true)]
    private readonly Email $email;

    #[ORM\Column(type: 'json')]
    private readonly array $roles;

    #[ORM\Column(type: 'boolean')]
    private bool $isActive;

    private function __construct(
        AdminId $adminId,
        string $firstName,
        string $lastName,
        Email $email,
        array $roles,
        bool $isActive,
    ) {
        $this->adminId = $adminId;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->roles = $roles;
        $this->isActive = $isActive;
    }

    public static function promote(
        string $firstName,
        string $lastName,
        Email $email,
        array $roles,
    ): self {
        return new self(AdminId::generate(), $firstName, $lastName, $email, $roles, true);
    }

    public function deactivate(): void
    {
        if (!$this->isActive) {
            throw AdminAlreadyDeactivatedException::withId($this->adminId);
        }
        $this->isActive = false;
    }

    public function getAdminId(): AdminId { return $this->adminId; }
    public function getFirstName(): string { return $this->firstName; }
    public function getLastName(): string { return $this->lastName; }
    public function getEmail(): Email { return $this->email; }
    public function isActive(): bool { return $this->isActive; }
    public function getRoles(): array { return $this->roles; }
}
