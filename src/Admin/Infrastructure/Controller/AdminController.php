<?php

namespace App\Admin\Infrastructure\Controller;

use App\Admin\Application\Handler\DeactivateAdmin\DeactivateAdminCommand;
use App\Admin\Application\Handler\DeactivateAdmin\DeactivateAdminHandler;
use App\Admin\Application\Handler\PromoteUserToAdmin\PromoteUserToAdminCommand;
use App\Admin\Application\Handler\PromoteUserToAdmin\PromoteUserToAdminHandler;
use App\Admin\Domain\AdminId;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/admins')]
final class AdminController extends AbstractController {

    #[Route('', methods: ['POST'])]
    public function promote(Request $request, PromoteUserToAdminHandler $handler): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $adminId = $handler->handle(new PromoteUserToAdminCommand(
            roles: $data['roles'],
        ));

        return $this->json(['id' => $adminId->toString()], 201);
    }

    #[Route('/{id}/deactivate', methods: ['POST'])]
    public function deactivate(string $id, DeactivateAdminHandler $handler): JsonResponse
    {
        $handler->handle(new DeactivateAdminCommand(
            adminId: AdminId::fromString($id),
        ));

        return $this->json(null, 204);
    }
}
