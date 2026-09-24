<?php

namespace App\Tests\Player\Domain;

use App\Player\Domain\Position;
use PHPUnit\Framework\TestCase;

final class PositionTest extends TestCase
{
    public function testAllExpectedPositionsExist(): void
    {
        $this->assertSame('goalkeeper', Position::GOALKEEPER->value);
        $this->assertSame('defender', Position::DEFENDER->value);
        $this->assertSame('midfielder', Position::MIDFIELDER->value);
        $this->assertSame('forward', Position::FORWARD->value);
    }

    public function testFromStringReturnsCorrectPosition(): void
    {
        $this->assertSame(Position::FORWARD, Position::from('forward'));
    }
}
