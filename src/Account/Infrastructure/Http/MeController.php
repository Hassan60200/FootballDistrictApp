<?php

declare(strict_types=1);

namespace App\Account\Infrastructure\Http;

use App\Account\Infrastructure\Security\SecurityUser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class MeController extends AbstractController
{
    #[Route('/api/me', name: 'api_me', methods: ['GET'])]
    #[IsGranted('ROLE_PLAYER')]
    public function __invoke(#[CurrentUser] SecurityUser $user): JsonResponse
    {
        return $this->json([
            'accountId' => $user->accountId(),
            'email' => $user->getUserIdentifier(),
            'role' => $user->getRoles()[0],
            'profile' => [
                'type' => $user->profileType()->value,
                'id' => $user->profileIdentifier(),
            ],
        ]);
    }
}
