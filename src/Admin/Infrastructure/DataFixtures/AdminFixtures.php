<?php

namespace App\Admin\Infrastructure\DataFixtures;

use App\Admin\Domain\Admin;
use App\Admin\Domain\Email;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class AdminFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $admin = Admin::promote(
            firstName: 'Hassan',
            lastName: 'Derkaoui',
            email: Email::fromString('hassan@cav.fr'),
            roles: ['ROLE_ADMIN', 'ROLE_SUPER_ADMIN'],
        );

        $manager->persist($admin);
        $manager->flush();
    }
}
