<?php

namespace App\Tests\Admin\Domain;

use App\Admin\Domain\Admin;
use App\Admin\Domain\Email;
use App\Admin\Domain\Exception\AdminAlreadyDeactivatedException;
use PHPUnit\Framework\TestCase;

final class AdminTest extends TestCase
{
    public function testPromoteCreatesActiveAdmin(): void
    {
        $admin = Admin::promote(
            firstName: 'Hassan',
            lastName: 'Derkaoui',
            email: Email::fromString('hassan@ca-venette.fr'),
            roles: ['ROLE_ADMIN'],
        );

        $this->assertTrue($admin->isActive());
        $this->assertSame(['ROLE_ADMIN'], $admin->getRoles());
    }

    public function testDeactivateSetsAdminAsInactive(): void
    {
        $admin = $this->createAdmin();

        $admin->deactivate();

        $this->assertFalse($admin->isActive());
    }

    public function testCannotDeactivateAlreadyDeactivatedAdmin(): void
    {
        $admin = $this->createAdmin();
        $admin->deactivate();

        $this->expectException(AdminAlreadyDeactivatedException::class);

        $admin->deactivate();
    }

    private function createAdmin(): Admin
    {
        return Admin::promote(
            firstName: 'Hassan',
            lastName: 'Derkaoui',
            email: Email::fromString('hassan@ca-venette.fr'),
            roles: ['ROLE_ADMIN'],
        );
    }
}
