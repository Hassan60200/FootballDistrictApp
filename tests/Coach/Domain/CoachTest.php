<?php

namespace App\Tests\Coach\Domain;

use App\Coach\Domain\Coach;
use App\Coach\Domain\Email;
use PHPUnit\Framework\TestCase;

final class CoachTest extends TestCase
{
    public function testRegisterCreatesActiveCoach(): void
    {
        $coach = Coach::register(
            firstName: 'Zinedine',
            lastName: 'Zidane',
            email: Email::fromString('zidane@ca-venette.fr'),
        );

        $this->assertTrue($coach->isActive());
        $this->assertSame('Zinedine', $coach->getFirstName());
        $this->assertSame('Zidane', $coach->getLastName());
    }

    public function testRenameChangesCoachInformation(): void
    {
        $coach = $this->createCoach();

        $coach->rename(
            firstName: 'Zizou',
            lastName: 'Zidane',
            email: Email::fromString('nouveau@ca-venette.fr'),
        );

        $this->assertSame('Zizou', $coach->getFirstName());
        $this->assertSame('nouveau@ca-venette.fr', $coach->getEmail()->toString());
    }

    public function testDeactivateSetsCoachAsInactive(): void
    {
        $coach = $this->createCoach();

        $coach->deactivate();

        $this->assertFalse($coach->isActive());
    }

    private function createCoach(): Coach
    {
        return Coach::register(
            firstName: 'Zinedine',
            lastName: 'Zidane',
            email: Email::fromString('zidane@ca-venette.fr'),
        );
    }
}
