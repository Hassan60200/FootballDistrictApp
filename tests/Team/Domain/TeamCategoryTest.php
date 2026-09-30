<?php

namespace App\Tests\Team\Domain;

use App\Team\Domain\TeamCategory;
use PHPUnit\Framework\TestCase;

final class TeamCategoryTest extends TestCase
{
    public function testAllExpectedCategoriesExist(): void
    {
        $this->assertSame('senior1', TeamCategory::SENIOR1->value);
        $this->assertSame('senior2', TeamCategory::SENIOR2->value);
        $this->assertSame('senior3', TeamCategory::SENIOR3->value);
        $this->assertSame('u18', TeamCategory::U18->value);
    }

    public function testFromStringReturnsCorrectCategory(): void
    {
        $this->assertSame(TeamCategory::SENIOR1, TeamCategory::from('senior1'));
    }
}
