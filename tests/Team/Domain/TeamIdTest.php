<?php

namespace App\Tests\Team\Domain;

use App\Team\Domain\TeamId;
use PHPUnit\Framework\TestCase;

final class TeamIdTest extends TestCase
{
    public function testGenerateCreatesUniqueIds(): void
    {
        $id1 = TeamId::generate();
        $id2 = TeamId::generate();

        $this->assertFalse($id1->equals($id2));
    }

    public function testFromStringAndToStringRoundTrip(): void
    {
        $original = TeamId::generate();
        $reconstructed = TeamId::fromString($original->toString());

        $this->assertTrue($original->equals($reconstructed));
    }

    public function testToStringReturnsAStringType(): void
    {
        $id = TeamId::generate();

        $this->assertIsString($id->toString());
    }
}
