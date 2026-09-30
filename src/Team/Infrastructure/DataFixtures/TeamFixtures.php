<?php

namespace App\Team\Infrastructure\DataFixtures;

use App\Team\Domain\Team;
use App\Team\Domain\TeamCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class TeamFixtures extends Fixture
{
    public const SENIOR_A_REFERENCE = 'team-senior-a';
    public const SENIOR_B_REFERENCE = 'team-senior-b';

    public function load(ObjectManager $manager): void
    {
        $seniorA = Team::create('Seniors A', TeamCategory::SENIOR1);
        $manager->persist($seniorA);
        $this->addReference(self::SENIOR_A_REFERENCE, $seniorA);

        $seniorB = Team::create('Seniors B', TeamCategory::SENIOR2);
        $manager->persist($seniorB);
        $this->addReference(self::SENIOR_B_REFERENCE, $seniorB);

        $manager->flush();
    }
}
