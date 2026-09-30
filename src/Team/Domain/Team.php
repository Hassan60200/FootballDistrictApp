<?php

namespace App\Team\Domain;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'teams')]
final class Team
{
    #[ORM\Id]
    #[ORM\Column(type: 'team_id', unique: true)]
    private readonly TeamId $id;

    #[ORM\Column(type: 'string')]
    private string $name;

    #[ORM\Column(type: 'string', enumType: TeamCategory::class)]
    private TeamCategory $category;

    private function __construct(TeamId $id, string $name, TeamCategory $category)
    {
        $this->id = $id;
        $this->name = $name;
        $this->category = $category;
    }

    public static function create(string $name, TeamCategory $category): self
    {
        return new self(TeamId::generate(), $name, $category);
    }

    public function getId(): TeamId
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCategory(): TeamCategory
    {
        return $this->category;
    }

    public function rename(string $name, TeamCategory $category): void
    {
        $this->name = $name;
        $this->category = $category;
    }
}
