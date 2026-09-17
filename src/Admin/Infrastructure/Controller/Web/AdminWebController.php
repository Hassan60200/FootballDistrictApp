<?php

namespace App\Admin\Infrastructure\Controller\Web;

use App\Admin\Application\Handler\ListAdmins\ListAdminsHandler;
use App\Admin\Application\Handler\ListAdmins\ListAdminsQuery;
use App\Admin\Application\Handler\PromoteUserToAdmin\PromoteUserToAdminCommand;
use App\Admin\Application\Handler\PromoteUserToAdmin\PromoteUserToAdminHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AdminWebController extends AbstractController
{
    #[Route('/admin/promote', name: 'admin_web_promote', methods: ['GET', 'POST'])]
    public function promote(Request $request, PromoteUserToAdminHandler $handler): Response
    {
        $success = null;
        $error = null;

        if ($request->isMethod('POST')) {
            $roles = $request->request->all('roles');
            $firstName = $request->request->get('firstName');
            $lastName = $request->request->get('lastName');
            $email = $request->request->get('email');

            try {
                $adminId = $handler->handle(new PromoteUserToAdminCommand(
                    firstName: $firstName,
                    lastName: $lastName,
                    email: $email,
                    roles: $roles,
                ));
                $success = $adminId->toString();
            } catch (\DomainException $e) {
                $error = $e->getMessage();
            }
        }

        return $this->render('admin/promote.html.twig', [
            'success' => $success,
            'error' => $error,
        ]);
    }

    #[Route('/admin/list', name: 'admin_web_list', methods: ['GET'])]
    public function listAdmin(ListAdminsHandler $handler): Response
    {
        $admins = $handler->handle(new ListAdminsQuery());

        return $this->render('admin/list.html.twig', [
            'admins' => $admins
        ]);
    }
}
