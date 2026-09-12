<?php

namespace App\Coach\Domain;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'coachs')]
final class Coach
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', unique: true)]
    private readonly CoachId $id;

    #[ORM\Column(type: 'string')]
    private readonly string $firstName;

    #[ORM\Column(type: 'string')]
    private readonly string $lastName;

    #[ORM\Column(type: 'coach_email', unique: true)]
    private readonly Email $email;

    #[ORM\Column(type: 'boolean')]
    private bool $isActive;

    private function __construct(
        CoachId $id,
        string $firstName,
        string $lastName,
        Email $email,
        bool $isActive,
    ) {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->isActive = $isActive;
    }

    public static function register(
        string $firstName,
        string $lastName,
        Email $email,
    ): self {
        return new self(CoachId::generate(), $firstName, $lastName, $email, true);
    }

    public function deactivate(): void
    {
        $this->isActive = false;
    }

    public function getId(): CoachId { return $this->id; }
    public function getFirstName(): string { return $this->firstName; }
    public function getLastName(): string { return $this->lastName; }
    public function getEmail(): Email { return $this->email; }
    public function isActive(): bool { return $this->isActive; }
}
