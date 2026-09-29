<?php

namespace App\Match\Infrastructure\Controller\Web;

use App\Match\Application\Handler\ScheduleMatch\ScheduleMatchCommand;
use App\Match\Application\Handler\ScheduleMatch\ScheduleMatchHandler;
use App\Match\Application\Handler\SummonPlayer\SummonPlayerCommand;
use App\Match\Application\Handler\SummonPlayer\SummonPlayerHandler;
use App\Match\Application\Query\ListMatches\ListMatchesHandler;
use App\Match\Application\Query\ListMatches\ListMatchesQuery;
use App\Match\Domain\MatchId;
use App\Match\Domain\Repository\MatchRepositoryInterface;
use App\Player\Domain\PlayerId;
use App\Player\Domain\Repository\PlayerRepositoryInterface;
use App\Team\Domain\Repository\TeamRepositoryInterface;
use App\Team\Domain\TeamId;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/matches')]
final class MatchWebController extends AbstractController
{
    #[Route('/new', name: 'match_web_create', methods: ['GET', 'POST'])]
    public function createMatch(
        Request $request,
        ScheduleMatchHandler $handler,
        TeamRepositoryInterface $teams,
    ): Response {
        $success = null;
        $error = null;

        if ($request->isMethod('POST')) {
            try {
                $homeTeam = $teams->findById(TeamId::fromString($request->request->get('homeTeamId')));
                if ($homeTeam === null) {
                    throw new \DomainException('Équipe sélectionnée introuvable.');
                }

                $date = new \DateTimeImmutable($request->request->get('date'));

                $matchId = $handler->handle(new ScheduleMatchCommand(
                    externalId: uniqid('manual_'),
                    date: $date,
                    time: $request->request->get('time'),
                    competitionName: $request->request->get('competitionName'),
                    homeClubName: $homeTeam->getName(),
                    homeClubLogoUrl: '',
                    homeTeamId: $homeTeam->getId(),
                    awayClubName: $request->request->get('awayClubName'),
                    awayClubLogoUrl: '',
                    awayTeamId: null,
                ));

                $success = $matchId->toString();
            } catch (\DomainException $e) {
                $error = $e->getMessage();
            }
        }

        return $this->render('match/create.html.twig', [
            'teams' => $teams->findAll(),
            'success' => $success,
            'error' => $error,
        ]);
    }

    #[Route('/list', name: 'match_web_list', methods: ['GET'])]
    public function listMatches(ListMatchesHandler $handler): Response
    {
        $matches = $handler->handle(new ListMatchesQuery());

        return $this->render('match/list.html.twig', [
            'matches' => $matches,
        ]);
    }

    #[Route('/{id}/summon', name: 'match_web_summon', methods: ['GET', 'POST'])]
    public function summonPlayer(
        string $id,
        Request $request,
        MatchRepositoryInterface $matches,
        PlayerRepositoryInterface $players,
        SummonPlayerHandler $handler,
    ): Response {
        $match = $matches->findById(MatchId::fromString($id));
        if ($match === null) {
            throw $this->createNotFoundException();
        }

        $success = null;
        $error = null;

        if ($request->isMethod('POST')) {
            try {
                $handler->handle(new SummonPlayerCommand(
                    matchId: MatchId::fromString($id),
                    playerId: PlayerId::fromString($request->request->get('playerId')),
                ));
                $success = true;
                $match = $matches->findById(MatchId::fromString($id));
            } catch (\DomainException $e) {
                $error = $e->getMessage();
            }
        }

        $summonedPlayers = array_map(
            static fn ($playerId) => $players->findById($playerId),
            $match->getSummonedPlayers(),
        );

        return $this->render('match/summon.html.twig', [
            'match' => $match,
            'allPlayers' => $players->findAll(),
            'summonedPlayers' => array_filter($summonedPlayers),
            'success' => $success,
            'error' => $error,
        ]);
    }
}
