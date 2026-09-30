<?php

namespace App\Coach\Domain;

use App\Coach\Domain\Exception\CoachAlreadyDeactivatedException;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'coaches')]
final class Coach
{
    #[ORM\Id]
    #[ORM\Column(type: 'coach_id', unique: true)]
    private readonly CoachId $id;

    #[ORM\Column(type: 'string')]
    private  string $firstName;

    #[ORM\Column(type: 'string')]
    private  string $lastName;

    #[ORM\Column(type: 'coach_email', unique: true)]
    private  Email $email;

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
        if (!$this->isActive) {
            throw CoachAlreadyDeactivatedException::withId($this->id);
        }
        $this->isActive = false;
    }

    public function getId(): CoachId { return $this->id; }
    public function getFirstName(): string { return $this->firstName; }
    public function getLastName(): string { return $this->lastName; }
    public function getEmail(): Email { return $this->email; }
    public function isActive(): bool { return $this->isActive; }

    public function rename(string $firstName, string $lastName, Email $email): void
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
    }
}
