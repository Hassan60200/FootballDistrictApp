<?php

namespace App\Player\Infrastructure\Controller\Web;

use App\Player\Application\Handler\ListPlayers\ListPlayersHandler;
use App\Player\Application\Handler\ListPlayers\ListPlayersQuery;
use App\Player\Application\Handler\RegisterPlayer\RegisterPlayerHandler;
use App\Team\Application\Handler\CreateTeam\CreateTeamCommand;
use DomainException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/players')]
class PlayerWebController extends AbstractController
{
    #[Route('/new', name: 'player_web_create', methods: ['GET', 'POST'])]
    public function createPlayer(RegisterPlayerHandler $handler, Request $request,): Response
    {
        $success = null;
        $error = null;

        if ($request->isMethod('POST')) {
            try {
                $teamId = $handler->handle(new CreateTeamCommand(
                    name: $request->name,
                    category: $request->category,
                ));
                $success = $teamId->toString();
            } catch (DomainException $e) {
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
}
