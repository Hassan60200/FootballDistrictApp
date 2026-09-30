<?php

namespace App\Coach\Infrastructure\Controller\Web;

use App\Coach\Application\Handler\ListCoaches\ListCoachesHandler;
use App\Coach\Application\Handler\ListCoaches\ListCoachesQuery;
use App\Coach\Application\Handler\RegisterCoach\RegisterCoachCommand;
use App\Coach\Application\Handler\RegisterCoach\RegisterCoachHandler;
use App\Coach\Application\Handler\UpdateCoach\UpdateCoachCommand;
use App\Coach\Application\Handler\UpdateCoach\UpdateCoachHandler;
use App\Coach\Application\Handler\DeleteCoach\DeleteCoachCommand;
use App\Coach\Application\Handler\DeleteCoach\DeleteCoachHandler;
use App\Coach\Domain\CoachId;
use App\Coach\Domain\Repository\CoachRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/coaches')]
class CoachWebController extends AbstractController
{
    #[Route('/new', name: 'coach_web_create', methods: ['GET', 'POST'])]
    public function createCoach(Request $request, RegisterCoachHandler $handler): Response
    {
        $success = null;
        $error = null;

        if ($request->isMethod('POST')) {
            try {
                $coachId = $handler->handle(new RegisterCoachCommand(
                    firstName: $request->request->get('firstName'),
                    lastName: $request->request->get('lastName'),
                    email: $request->request->get('email'),
                ));
                $success = $coachId->toString();
                return $this->redirectToRoute('coach_web_list');
            } catch (\DomainException $e) {
                $error = $e->getMessage();
            }
        }

        return $this->render('coach/create.html.twig', [
            'success' => $success,
            'error' => $error,
        ]);
    }

    #[Route('/list', name: 'coach_web_list', methods: ['GET'])]
    public function listCoaches(ListCoachesHandler $handler): Response
    {
        $coaches = $handler->handle(new ListCoachesQuery());

        return $this->render('coach/list.html.twig', [
            'coaches' => $coaches,
        ]);
    }

    #[Route('/{id}/edit', name: 'coach_web_edit', methods: ['GET', 'POST'])]
    public function editCoach(
        string $id,
        Request $request,
        CoachRepositoryInterface $coaches,
        UpdateCoachHandler $handler,
    ): Response {
        $coach = $coaches->findById(CoachId::fromString($id));
        if ($coach === null) {
            throw $this->createNotFoundException();
        }

        $success = null;
        $error = null;

        if ($request->isMethod('POST')) {
            try {
                $handler->handle(new UpdateCoachCommand(
                    coachId: CoachId::fromString($id),
                    firstName: $request->request->get('firstName'),
                    lastName: $request->request->get('lastName'),
                    email: $request->request->get('email'),
                ));
                $success = true;
                $coach = $coaches->findById(CoachId::fromString($id));
                return $this->redirectToRoute('coach_web_list');
            } catch (\DomainException $e) {
                $error = $e->getMessage();
            }
        }

        return $this->render('coach/edit.html.twig', [
            'coach' => $coach,
            'success' => $success,
            'error' => $error,
        ]);
    }

    #[Route('/{id}/delete', name: 'coach_web_delete', methods: ['POST'])]
    public function deleteCoach(string $id, DeleteCoachHandler $handler): Response
    {
        $handler->handle(new DeleteCoachCommand(coachId: CoachId::fromString($id)));

        return $this->redirectToRoute('coach_web_list');
    }
}
