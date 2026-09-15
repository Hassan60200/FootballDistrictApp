<?php

namespace App\Team\Infrastructure\Controller;

use App\Team\Application\Handler\CreateTeam\CreateTeamCommand;
use App\Team\Application\Handler\CreateTeam\CreateTeamHandler;
use App\Team\Application\Query\GetTeamCalendar\GetTeamCalendarHandler;
use App\Team\Application\Query\GetTeamCalendar\GetTeamCalendarQuery;
use App\Team\Application\Query\ListTeams\ListTeamsHandler;
use App\Team\Application\Query\ListTeams\ListTeamsQuery;
use App\Team\Domain\TeamId;
use App\Team\Infrastructure\Controller\Request\CreateTeamRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/teams')]
final class TeamController extends AbstractController
{
    #[Route('', methods: ['POST'])]
    public function create(#[MapRequestPayload] CreateTeamRequest $request, CreateTeamHandler $handler): JsonResponse
    {
        $teamId = $handler->handle(new CreateTeamCommand(
            name: $request->name,
            category: $request->category,
        ));

        return $this->json(['id' => $teamId->toString()], 201);
    }

    #[Route('/list', methods: ['GET'])]
    public function list(ListTeamsHandler $handler): JsonResponse
    {
        $teams = $handler->handle(new ListTeamsQuery());

        $data = array_map(static function ($team) {
            return [
                'id' => $team->getId()->toString(),
                'name' => $team->getName(),
                'category' => $team->getCategory()->value,
            ];
        }, $teams);

        return $this->json($data, 200);
    }

    #[Route('/{id}/calendar', methods: ['GET'])]
    public function calendar(string $id, GetTeamCalendarHandler $handler): JsonResponse
    {
        $matches = $handler->handle(new GetTeamCalendarQuery(TeamId::fromString($id)));

        $data = array_map(static function ($match) {
            return [
                'id' => $match->getId()->toString(),
                'scheduledAt' => $match->getScheduledAt()->toDateTimeImmutable()->format(DATE_ATOM),
                'competitionName' => $match->getCompetitionName(),
                'homeClub' => $match->getHomeClub()->getName(),
                'awayClub' => $match->getAwayClub()->getName(),
            ];
        }, $matches);

        return $this->json($data, 200);
    }
}
