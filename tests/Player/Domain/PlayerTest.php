<?php

namespace App\Tests\Player\Domain;

use App\Player\Domain\Email;
use App\Player\Domain\Player;
use App\Player\Domain\Position;
use PHPUnit\Framework\TestCase;

final class PlayerTest extends TestCase
{
    public function testRegisterCreatesActivePlayer(): void
    {
        $player = Player::register(
            firstName: 'Karim',
            lastName: 'Benzema',
            age: 36,
            email: Email::fromString('karim@ca-venette.fr'),
            position: Position::FORWARD,
        );

        $this->assertTrue($player->isActive());
        $this->assertSame('Karim', $player->getFirstName());
        $this->assertSame(Position::FORWARD, $player->getPosition());
    }

    public function testUpdateChangesPlayerInformation(): void
    {
        $player = $this->createPlayer();

        $player->update(
            firstName: 'Karim',
            lastName: 'Benzema',
            age: 37,
            email: Email::fromString('nouveau@ca-venette.fr'),
            position: Position::MIDFIELDER,
        );

        $this->assertSame(37, $player->getAge());
        $this->assertSame('nouveau@ca-venette.fr', $player->getEmail()->toString());
        $this->assertSame(Position::MIDFIELDER, $player->getPosition());
    }

    private function createPlayer(): Player
    {
        return Player::register(
            firstName: 'Karim',
            lastName: 'Benzema',
            age: 36,
            email: Email::fromString('karim@ca-venette.fr'),
            position: Position::FORWARD,
        );
    }
}
