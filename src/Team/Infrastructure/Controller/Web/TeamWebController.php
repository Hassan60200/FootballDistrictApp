<?php

namespace App\Team\Infrastructure\Controller\Web;


use App\Player\Application\Handler\RegisterPlayer\RegisterPlayerCommand;
use App\Player\Domain\Position;
use App\Team\Application\Handler\CreateTeam\CreateTeamCommand;
use App\Team\Application\Handler\CreateTeam\CreateTeamHandler;
use App\Team\Application\Handler\DeleteTeam\DeleteTeamCommand;
use App\Team\Application\Handler\DeleteTeam\DeleteTeamHandler;
use App\Team\Application\Handler\UpdateTeam\UpdateTeamCommand;
use App\Team\Application\Handler\UpdateTeam\UpdateTeamHandler;
use App\Team\Application\Query\ListTeams\ListTeamsHandler;
use App\Team\Application\Query\ListTeams\ListTeamsQuery;
use App\Team\Domain\Repository\TeamRepositoryInterface;
use App\Team\Domain\TeamCategory;
use App\Team\Domain\TeamId;
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

    #[Route('/{id}/edit', name: 'team_web_edit', methods: ['GET', 'POST'])]
    public function editTeam(string $id, Request $request, TeamRepositoryInterface $teams, UpdateTeamHandler $handler): Response
    {
        $team = $teams->findById(TeamId::fromString($id));
        if ($team === null) {
            throw $this->createNotFoundException();
        }

        $success = null;
        $error = null;

        if ($request->isMethod('POST')) {
            try {
                $handler->handle(new UpdateTeamCommand(
                    teamId: TeamId::fromString($id),
                    name: $request->request->get('name'),
                    category: TeamCategory::from($request->request->get('category')),
                ));
                $success = true;

                return $this->redirectToRoute('team_web_list');
            } catch (\DomainException $e) {
                $error = $e->getMessage();
            }
        }

        return $this->render('team/edit.html.twig', [
            'team' => $team,
            'success' => $success,
            'error' => $error,
        ]);
    }

    #[Route('/{id}/delete', name: 'team_web_delete', methods: ['POST'])]
    public function deleteTeam(string $id, DeleteTeamHandler $handler): Response
    {
        $handler->handle(new DeleteTeamCommand(teamId: TeamId::fromString($id)));

        return $this->redirectToRoute('team_web_list');
    }
}
