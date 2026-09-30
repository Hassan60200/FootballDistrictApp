<?php

namespace App\Tests\Team\Domain;

use App\Team\Domain\Team;
use App\Team\Domain\TeamCategory;
use PHPUnit\Framework\TestCase;

final class TeamTest extends TestCase
{
    public function testCreateBuildsTeamWithGivenNameAndCategory(): void
    {
        $team = Team::create('Seniors A', TeamCategory::SENIOR1);

        $this->assertSame('Seniors A', $team->getName());
        $this->assertSame(TeamCategory::SENIOR1, $team->getCategory());
    }

    public function testRenameChangesNameAndCategory(): void
    {
        $team = Team::create('Seniors A', TeamCategory::SENIOR1);

        $team->rename('Seniors A - Renommée', TeamCategory::SENIOR2);

        $this->assertSame('Seniors A - Renommée', $team->getName());
        $this->assertSame(TeamCategory::SENIOR2, $team->getCategory());
    }

    public function testTwoTeamsHaveDifferentIds(): void
    {
        $team1 = Team::create('Seniors A', TeamCategory::SENIOR1);
        $team2 = Team::create('Seniors B', TeamCategory::SENIOR2);

        $this->assertFalse($team1->getId()->equals($team2->getId()));
    }
}
