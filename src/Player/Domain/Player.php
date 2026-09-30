<?php

namespace App\Player\Domain;

use App\Player\Domain\Exception\PlayerAlreadyDeactivatedException;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'players')]
final class Player
{
    #[ORM\Id]
    #[ORM\Column(type: 'player_id', unique: true)]
    private readonly PlayerId $id;

    #[ORM\Column(type: 'string')]
    private string $firstName;

    #[ORM\Column(type: 'string')]
    private string $lastName;

    #[ORM\Column(type: 'integer')]
    private int $age;

    #[ORM\Column(type: 'player_email', unique: true)]
    private Email $email;

    #[ORM\Column(type: 'string', enumType: Position::class)]
    private Position $position;

    #[ORM\Column(type: 'boolean')]
    private bool $isActive;

    private function __construct(
        PlayerId $id,
        string   $firstName,
        string   $lastName,
        int      $age,
        Email    $email,
        Position $position,
        bool     $isActive,
    )
    {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->age = $age;
        $this->email = $email;
        $this->position = $position;
        $this->isActive = $isActive;
    }

    public static function register(
        string   $firstName,
        string   $lastName,
        int      $age,
        Email    $email,
        Position $position,
    ): self
    {
        return new self(PlayerId::generate(), $firstName, $lastName, $age, $email, $position, true);
    }

    public function deactivate(): void
    {
        if (!$this->isActive) {
            throw PlayerAlreadyDeactivatedException::withId($this->id);
        }
        $this->isActive = false;
    }

    public function getId(): PlayerId
    {
        return $this->id;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getAge(): int
    {
        return $this->age;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getPosition(): Position
    {
        return $this->position;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function update(string $firstName, string $lastName, int $age, Email $email, Position $position): void
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->age = $age;
        $this->email = $email;
        $this->position = $position;
    }
}
