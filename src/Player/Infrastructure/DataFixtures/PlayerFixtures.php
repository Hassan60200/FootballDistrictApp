<?php

namespace App\Player\Infrastructure\DataFixtures;

use App\Player\Domain\Email;
use App\Player\Domain\Player;
use App\Player\Domain\Position;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class PlayerFixtures extends Fixture
{
    private const FIRST_NAMES = ['Karim', 'Antoine', 'Kylian', 'Paul', 'Raphael', 'Ousmane', 'N\'Golo', 'Hugo', 'Aurelien', 'Theo', 'Benjamin', 'William', 'Adrien', 'Jules', 'Marcus', 'Randal', 'Jonathan', 'Ibrahima', 'Presnel', 'Lucas'];
    private const LAST_NAMES = ['Benzema', 'Griezmann', 'Mbappe', 'Pogba', 'Varane', 'Dembele', 'Kante', 'Lloris', 'Tchouameni', 'Hernandez', 'Pavard', 'Saliba', 'Rabiot', 'Kounde', 'Thuram', 'Kolo-Muani', 'Clauss', 'Konate', 'Kimpembe', 'Digne'];
    private const POSITIONS = [Position::GOALKEEPER, Position::DEFENDER, Position::MIDFIELDER, Position::FORWARD];

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 20; $i++) {
            $player = Player::register(
                firstName: self::FIRST_NAMES[$i],
                lastName: self::LAST_NAMES[$i],
                age: random_int(18, 35),
                email: Email::fromString(strtolower(self::FIRST_NAMES[$i]) . '.' . strtolower(self::LAST_NAMES[$i]) . '@cav.fr'),
                position: self::POSITIONS[$i % 4],
            );

            $manager->persist($player);
        }

        $manager->flush();
    }
}
