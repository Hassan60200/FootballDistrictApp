<?php

namespace App\Tests\Admin\Infrastructure\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class AdminControllerTest extends WebTestCase
{
    public function testPromoteCreatesAdminAndReturns201(): void
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/admins',
            server: ['CONTENT_TYPE' => 'application/json'],
            content: json_encode([
                'firstName' => 'Hassan',
                'lastName' => 'Derkaoui',
                'email' => 'hassan@ca-venette.fr',
                'roles' => ['ROLE_ADMIN'],
            ])
        );

        $this->assertResponseStatusCodeSame(201);
        $this->assertArrayHasKey('id', json_decode($client->getResponse()->getContent(), true));
    }

    public function testPromoteWithInvalidEmailReturns422(): void
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/admins',
            server: ['CONTENT_TYPE' => 'application/json'],
            content: json_encode([
                'firstName' => 'Hassan',
                'lastName' => 'Derkaoui',
                'email' => 'pas-un-email',
                'roles' => ['ROLE_ADMIN'],
            ])
        );

        $this->assertResponseStatusCodeSame(422);
    }
}
