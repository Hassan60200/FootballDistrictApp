<?php

namespace App\Coach\Infrastructure\DataFixtures;

use App\Coach\Domain\Coach;
use App\Coach\Domain\Email;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class CoachFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $coaches = [
            ['Zinedine', 'Zidane'],
            ['Didier', 'Deschamps'],
        ];

        foreach ($coaches as [$firstName, $lastName]) {
            $coach = Coach::register(
                firstName: $firstName,
                lastName: $lastName,
                email: Email::fromString(strtolower($firstName) . '.' . strtolower($lastName) . '@cav.fr'),
            );

            $manager->persist($coach);
        }

        $manager->flush();
    }
}
