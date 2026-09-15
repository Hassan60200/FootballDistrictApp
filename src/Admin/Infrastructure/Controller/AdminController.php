<?php

namespace App\Admin\Infrastructure\Controller;

use App\Admin\Application\Handler\DeactivateAdmin\DeactivateAdminCommand;
use App\Admin\Application\Handler\DeactivateAdmin\DeactivateAdminHandler;
use App\Admin\Application\Handler\ListAdmins\ListAdminsHandler;
use App\Admin\Application\Handler\ListAdmins\ListAdminsQuery;
use App\Admin\Application\Handler\PromoteUserToAdmin\PromoteUserToAdminCommand;
use App\Admin\Application\Handler\PromoteUserToAdmin\PromoteUserToAdminHandler;
use App\Admin\Domain\AdminId;
use App\Admin\Infrastructure\Controller\Request\PromoteAdminRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/admins')]
final class AdminController extends AbstractController
{
    #[Route('', methods: ['POST'])]
    public function promote(#[MapRequestPayload] PromoteAdminRequest $request, PromoteUserToAdminHandler $handler): JsonResponse
    {
        $adminId = $handler->handle(new PromoteUserToAdminCommand(
            roles: $request->roles,
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


    #[Route('/list', methods: ['GET'])]
    public function list(ListAdminsHandler $handler): JsonResponse
    {
        $admins = $handler->handle(new ListAdminsQuery());

        $data = array_map(function ($admin) {
            return [
                'id' => $admin->getAdminId()->toString(),
                'isActive' => $admin->isActive(),
                'roles' => $admin->getRoles(),
            ];
        }, $admins);

        return $this->json($data, 200);
    }
}
