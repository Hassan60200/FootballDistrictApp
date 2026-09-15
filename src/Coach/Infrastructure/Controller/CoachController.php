<?php

namespace App\Coach\Infrastructure\Controller;

use App\Coach\Application\Handler\DeactivateCoach\DeactivateCoachCommand;
use App\Coach\Application\Handler\DeactivateCoach\DeactivateCoachHandler;
use App\Coach\Application\Handler\ListCoaches\ListCoachesHandler;
use App\Coach\Application\Handler\ListCoaches\ListCoachesQuery;
use App\Coach\Application\Handler\RegisterCoach\RegisterCoachCommand;
use App\Coach\Application\Handler\RegisterCoach\RegisterCoachHandler;
use App\Coach\Domain\CoachId;
use App\Coach\Infrastructure\Controller\Request\RegisterCoachRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/coaches')]
final class CoachController extends AbstractController
{
    #[Route('', methods: ['POST'])]
    public function register(#[MapRequestPayload] RegisterCoachRequest $request, RegisterCoachHandler $handler): JsonResponse
    {
        $coachId = $handler->handle(new RegisterCoachCommand(
            firstName: $request->firstName,
            lastName: $request->lastName,
            email: $request->email,
        ));

        return $this->json(['id' => $coachId->toString()], 201);
    }

    #[Route('/{id}/deactivate', methods: ['POST'])]
    public function deactivate(string $id, DeactivateCoachHandler $handler): JsonResponse
    {
        $handler->handle(new DeactivateCoachCommand(
            coachId: CoachId::fromString($id),
        ));

        return $this->json(null, 204);
    }

    #[Route('/list', methods: ['GET'])]
    public function list(ListCoachesHandler $handler): JsonResponse
    {
        $coaches = $handler->handle(new ListCoachesQuery());

        $data = array_map(static function ($coach) {
            return [
                'id' => $coach->getId()->toString(),
                'firstName' => $coach->getFirstName(),
                'lastName' => $coach->getLastName(),
                'email' => $coach->getEmail()->toString(),
                'isActive' => $coach->isActive(),
            ];
        }, $coaches);

        return $this->json($data, 200);
    }
}
