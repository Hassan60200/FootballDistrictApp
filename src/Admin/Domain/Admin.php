<?php

namespace App\Admin\Domain;

use App\Admin\Domain\Exception\AdminAlreadyDeactivatedException;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'admins')]
final class Admin
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', unique: true)]
    private readonly AdminId $adminId;

    #[ORM\Column(type: 'boolean')]
    private bool $isActive;

    #[ORM\Column(type: 'string')]
    private readonly string $roles;

    private function __construct(
        AdminId $adminId,
        bool    $isActive,
        string  $roles,
    )
    {
        $this->adminId = $adminId;
        $this->isActive = $isActive;
        $this->roles = $roles;
    }

    public static function promote(string $roles): self
    {
        return new self(AdminId::generate(), true, $roles);
    }

    public function deactivate(): void
    {
        if (!$this->isActive) {
            throw AdminAlreadyDeactivatedException::withId($this->adminId);
        }
        $this->isActive = false;
    }

    public function getAdminId(): AdminId
    {
        return $this->adminId;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function getRoles(): string
    {
        return $this->roles;
    }
}
