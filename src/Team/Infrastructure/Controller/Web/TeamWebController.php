<?php

namespace App\Team\Infrastructure\Controller\Web;


use App\Player\Application\Handler\RegisterPlayer\RegisterPlayerCommand;
use App\Player\Domain\Position;
use App\Team\Application\Handler\CreateTeam\CreateTeamCommand;
use App\Team\Application\Handler\CreateTeam\CreateTeamHandler;
use App\Team\Application\Query\ListTeams\ListTeamsHandler;
use App\Team\Application\Query\ListTeams\ListTeamsQuery;
use App\Team\Domain\TeamCategory;
use DomainException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/teams')]
class TeamWebController extends AbstractController
{

        #[Route('/new', name: 'team_web_create', methods: ['GET', 'POST'])]
    public function createTeam(Request $request, CreateTeamHandler $handler): Response
    {
        {
            $success = null;
            $error = null;

            if ($request->isMethod('POST')) {
                try {
                    $teamId = $handler->handle(new CreateTeamCommand(
                        name: $request->request->get('name'),
                        category: TeamCategory::from($request->request->get('category')),
                    ));
                    $success = $teamId->toString();
                } catch (\DomainException $e) {
                    $error = $e->getMessage();
                }
            }

            return $this->render('team/create.html.twig', [
                'success' => $success,
                'error' => $error,
            ]);
        }
    }

    #[Route('/list', name: 'team_web_list', methods: ['GET'])]
    public function listTeams(ListTeamsHandler $handler): Response
    {
        $teams = $handler->handle(new ListTeamsQuery());

        return $this->render('team/list.html.twig', [
            'teams' => $teams,
        ]);
    }
}
