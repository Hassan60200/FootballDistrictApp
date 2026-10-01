<?php

namespace App\Match\Infrastructure\Controller\Web;

use App\Match\Application\Handler\CancelMatch\CancelMatchCommand;
use App\Match\Application\Handler\CancelMatch\CancelMatchHandler;
use App\Match\Application\Handler\ChangeMatchStatus\ChangeMatchStatusCommand;
use App\Match\Application\Handler\ChangeMatchStatus\ChangeMatchStatusHandler;
use App\Match\Application\Handler\ScheduleMatch\ScheduleMatchCommand;
use App\Match\Application\Handler\ScheduleMatch\ScheduleMatchHandler;
use App\Match\Application\Handler\SummonPlayer\SummonPlayerCommand;
use App\Match\Application\Handler\SummonPlayer\SummonPlayerHandler;
use App\Match\Application\Handler\UpdateMatch\UpdateMatchCommand;
use App\Match\Application\Handler\UpdateMatch\UpdateMatchHandler;
use App\Match\Application\Query\ListMatches\ListMatchesHandler;
use App\Match\Application\Query\ListMatches\ListMatchesQuery;
use App\Match\Domain\MatchId;
use App\Match\Domain\MatchStatus;
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
            $playerIds = $request->request->all('playerIds');
            $summonedCount = 0;

            foreach ($playerIds as $playerId) {
                try {
                    $handler->handle(new SummonPlayerCommand(
                        matchId: MatchId::fromString($id),
                        playerId: PlayerId::fromString($playerId),
                    ));
                    $summonedCount++;
                } catch (\DomainException $e) {
                    $error = $e->getMessage();
                    break;
                }
            }

            if ($summonedCount > 0) {
                $success = sprintf('%d joueur%s convoqué%s.', $summonedCount, $summonedCount > 1 ? 's' : '', $summonedCount > 1 ? 's' : '');
            }

            $match = $matches->findById(MatchId::fromString($id));
        }

        $summonedPlayerIds = $match->getSummonedPlayers();

        $summonedPlayers = array_filter(array_map(
            static fn ($playerId) => $players->findById($playerId),
            $summonedPlayerIds,
        ));

        $availablePlayers = array_filter(
            $players->findAll(),
            static function ($player) use ($summonedPlayerIds) {
                foreach ($summonedPlayerIds as $summonedId) {
                    if ($summonedId->equals($player->getId())) {
                        return false;
                    }
                }
                return true;
            }
        );

        return $this->render('match/summon.html.twig', [
            'match' => $match,
            'availablePlayers' => $availablePlayers,
            'summonedPlayers' => $summonedPlayers,
            'success' => $success,
            'error' => $error,
        ]);
    }

    #[Route('/{id}/cancel', name: 'match_web_cancel', methods: ['POST'])]
    public function cancelMatch(string $id, CancelMatchHandler $handler): Response
    {
        try {
            $handler->handle(new CancelMatchCommand(matchId: MatchId::fromString($id)));
        } catch (\DomainException $e) {
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToRoute('match_web_list');
    }

    #[Route('/{id}/edit', name: 'match_web_edit', methods: ['POST'])]
    public function editMatch(string $id, Request $request, UpdateMatchHandler $handler): Response
    {
        try {
            $handler->handle(new UpdateMatchCommand(
                matchId: MatchId::fromString($id),
                date: new \DateTimeImmutable($request->request->get('date')),
                time: $request->request->get('time'),
                competitionName: $request->request->get('competitionName'),
                awayClubName: $request->request->get('awayClubName'),
            ));
        } catch (\DomainException $e) {
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToRoute('match_web_list');
    }

    #[Route('/{id}/status', name: 'match_web_change_status', methods: ['POST'])]
    public function changeStatus(string $id, Request $request, ChangeMatchStatusHandler $handler): Response
    {
        try {
            $handler->handle(new ChangeMatchStatusCommand(
                matchId: MatchId::fromString($id),
                status: MatchStatus::from($request->request->get('status')),
            ));
        } catch (\DomainException $e) {
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToRoute('match_web_list');
    }
}
