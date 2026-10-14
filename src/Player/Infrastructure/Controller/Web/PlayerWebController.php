<?php

namespace App\Player\Infrastructure\Controller\Web;

use App\Player\Application\Handler\DeletePlayer\DeletePlayerCommand;
use App\Player\Application\Handler\DeletePlayer\DeletePlayerHandler;
use App\Player\Application\Handler\ListPlayers\ListPlayersHandler;
use App\Player\Application\Handler\ListPlayers\ListPlayersQuery;
use App\Player\Application\Handler\RegisterPlayer\RegisterPlayerCommand;
use App\Player\Application\Handler\RegisterPlayer\RegisterPlayerHandler;
use App\Player\Application\Handler\UpdatePlayer\UpdatePlayerCommand;
use App\Player\Application\Handler\UpdatePlayer\UpdatePlayerHandler;
use App\Player\Domain\PhoneNumber;
use App\Player\Domain\PlayerId;
use App\Player\Domain\Position;
use App\Player\Domain\Repository\PlayerRepositoryInterface;
use App\Match\Application\Query\ListPlayerMatches\ListPlayerMatchesHandler;
use App\Match\Application\Query\ListPlayerMatches\ListPlayerMatchesQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/players')]
class PlayerWebController extends AbstractController
{
    #[Route('/new', name: 'player_web_create', methods: ['GET', 'POST'])]
    public function createPlayer(RegisterPlayerHandler $handler, Request $request): Response
    {
        $success = null;
        $error = null;

        if ($request->isMethod('POST')) {
            try {
                $playerId = $handler->handle(new RegisterPlayerCommand(
                    firstName: $request->request->get('firstName'),
                    lastName: $request->request->get('lastName'),
                    age: (int) $request->request->get('age'),
                    email: $request->request->get('email'),
                    position: Position::from($request->request->get('position')),
                    phoneNumber: PhoneNumber::fromString($request->request->get('phoneNumber')
                )));
                $success = $playerId->toString();
                return $this->redirectToRoute('player_web_list');
            } catch (\DomainException $e) {
                $error = $e->getMessage();
            }
        }

        return $this->render('player/create.html.twig', [
            'error' => $error,
            'success' => $success,
        ]);
    }
    #[Route('/list', name: 'player_web_list', methods: ['GET'])]
    public function listPlayers(ListPlayersHandler $handler): Response
    {
        $players = $handler->handle(new ListPlayersQuery());

        return $this->render('player/list.html.twig', [
            'players' => $players,
        ]);
    }

    #[Route('/{id}/edit', name: 'player_web_edit', methods: ['GET', 'POST'])]
    public function editPlayer(
        string $id,
        Request $request,
        PlayerRepositoryInterface $players,
        UpdatePlayerHandler $handler,
    ): Response {
        $player = $players->findById(PlayerId::fromString($id));
        if ($player === null) {
            throw $this->createNotFoundException();
        }

        $success = null;
        $error = null;

        if ($request->isMethod('POST')) {
            try {
                $handler->handle(new UpdatePlayerCommand(
                    playerId: PlayerId::fromString($id),
                    firstName: $request->request->get('firstName'),
                    lastName: $request->request->get('lastName'),
                    age: (int) $request->request->get('age'),
                    email: $request->request->get('email'),
                    position: Position::from($request->request->get('position')),
                ));
                $success = true;
                $player = $players->findById(PlayerId::fromString($id));
                return $this->redirectToRoute('player_web_list');
            } catch (\DomainException $e) {
                $error = $e->getMessage();
            }
        }

        return $this->render('player/edit.html.twig', [
            'player' => $player,
            'success' => $success,
            'error' => $error,
        ]);
    }

    #[Route('/{id}/delete', name: 'player_web_delete', methods: ['POST'])]
    public function deletePlayer(string $id, DeletePlayerHandler $handler): Response
    {
        $handler->handle(new DeletePlayerCommand(playerId: PlayerId::fromString($id)));

        return $this->redirectToRoute('player_web_list');
    }

    #[Route('/{id}', name: 'player_web_show', requirements: ['id' => '[0-9a-fA-F-]{36}'], methods: ['GET'])]
    public function showPlayer(
        string $id,
        PlayerRepositoryInterface $players,
        ListPlayerMatchesHandler $matchesHandler,
    ): Response {
        $playerId = PlayerId::fromString($id);

        $player = $players->findById($playerId);
        if ($player === null) {
            throw $this->createNotFoundException();
        }

        return $this->render('player/show.html.twig', [
            'player' => $player,
            'matches' => $matchesHandler->handle(new ListPlayerMatchesQuery($playerId)),
        ]);
    }
}
