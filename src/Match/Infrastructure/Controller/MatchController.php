<?php

namespace App\Match\Infrastructure\Controller;

use App\Match\Application\Handler\ScheduleMatch\ScheduleMatchCommand;
use App\Match\Application\Handler\ScheduleMatch\ScheduleMatchHandler;
use App\Match\Application\Handler\SummonPlayer\SummonPlayerCommand;
use App\Match\Application\Handler\SummonPlayer\SummonPlayerHandler;
use App\Match\Application\Query\ListMatches\ListMatchesHandler;
use App\Match\Application\Query\ListMatches\ListMatchesQuery;
use App\Match\Domain\MatchId;
use App\Match\Infrastructure\Controller\Request\ScheduleMatchRequest;
use App\Match\Infrastructure\Controller\Request\SummonPlayerRequest;
use App\Player\Domain\PlayerId;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/matches')]
final class MatchController extends AbstractController
{
    #[Route('', methods: ['POST'])]
    public function schedule(#[MapRequestPayload] ScheduleMatchRequest $request, ScheduleMatchHandler $handler): JsonResponse
    {
        $matchId = $handler->handle(new ScheduleMatchCommand(
            externalId: $request->externalId,
            date: $request->date,
            time: $request->time,
            competitionName: $request->competitionName,
            homeClubName: $request->homeClub->name,
            homeClubLogoUrl: $request->homeClub->logoUrl,
            homeTeamId: $request->homeClub->teamId,
            awayClubName: $request->awayClub->name,
            awayClubLogoUrl: $request->awayClub->logoUrl,
            awayTeamId: $request->awayClub->teamId,
        ));

        return $this->json(['id' => $matchId->toString()], 201);
    }

    #[Route('/list', methods: ['GET'])]
    public function list(ListMatchesHandler $handler): JsonResponse
    {
        $matches = $handler->handle(new ListMatchesQuery());

        $data = array_map(static function ($match) {
            return [
                'id' => $match->getId()->toString(),
                'scheduledAt' => $match->getScheduledAt()->toDateTimeImmutable()->format(DATE_ATOM),
                'competitionName' => $match->getCompetitionName(),
                'homeClub' => [
                    'name' => $match->getHomeClub()->getName(),
                    'logoUrl' => $match->getHomeClub()->getLogoUrl(),
                    'teamId' => $match->getHomeClub()->getTeamId()?->toString(),
                ],
                'awayClub' => [
                    'name' => $match->getAwayClub()->getName(),
                    'logoUrl' => $match->getAwayClub()->getLogoUrl(),
                    'teamId' => $match->getAwayClub()->getTeamId()?->toString(),
                ],
            ];
        }, $matches);

        return $this->json($data, 200);
    }

    #[Route('/{id}/summon', methods: ['POST'])]
    public function summon(
        string $id,
        #[MapRequestPayload] SummonPlayerRequest $request,
        SummonPlayerHandler $handler,
    ): JsonResponse {
        $handler->handle(new SummonPlayerCommand(
            matchId: MatchId::fromString($id),
            playerId: PlayerId::fromString($request->playerId),
        ));

        return $this->json(null, 204);
    }
}
