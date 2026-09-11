<?php

namespace App\Player\Infrastructure\Controller;

use App\Player\Application\Handler\DeactivatePlayer\DeactivatePlayerCommand;
use App\Player\Application\Handler\DeactivatePlayer\DeactivatePlayerHandler;
use App\Player\Application\Handler\ListPlayers\ListPlayersHandler;
use App\Player\Application\Handler\ListPlayers\ListPlayersQuery;
use App\Player\Application\Handler\RegisterPlayer\RegisterPlayerCommand;
use App\Player\Application\Handler\RegisterPlayer\RegisterPlayerHandler;
use App\Player\Domain\PlayerId;
use App\Player\Infrastructure\Controller\Request\RegisterPlayerRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/players')]
final class PlayerController extends AbstractController
{
    #[Route('', methods: ['POST'])]
    public function register(#[MapRequestPayload] RegisterPlayerRequest $request, RegisterPlayerHandler $handler): JsonResponse
    {
        $playerId = $handler->handle(new RegisterPlayerCommand(
            firstName: $request->firstName,
            lastName: $request->lastName,
            age: $request->age,
            email: $request->email,
            position: $request->position,
        ));

        return $this->json(['id' => $playerId->toString()], 201);
    }

    #[Route('/{id}/deactivate', methods: ['POST'])]
    public function deactivate(string $id, DeactivatePlayerHandler $handler): JsonResponse
    {
        $handler->handle(new DeactivatePlayerCommand(
            playerId: PlayerId::fromString($id),
        ));

        return $this->json(null, 204);
    }

    #[Route('/list', methods: ['GET'])]
    public function list(ListPlayersHandler $handler): JsonResponse
    {
        $players = $handler->handle(new ListPlayersQuery());

        $data = array_map(static function ($player) {
            return [
                'id' => $player->getId()->toString(),
                'firstName' => $player->getFirstName(),
                'lastName' => $player->getLastName(),
                'age' => $player->getAge(),
                'email' => $player->getEmail()->toString(),
                'position' => $player->getPosition()->value,
                'isActive' => $player->isActive(),
            ];
        }, $players);

        return $this->json($data, 200);
    }
}
