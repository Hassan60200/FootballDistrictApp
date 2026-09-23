<?php

namespace App\Team\Infrastructure\Controller\Web;


use App\Player\Application\Handler\RegisterPlayer\RegisterPlayerCommand;
use App\Player\Domain\Position;
use App\Team\Application\Handler\CreateTeam\CreateTeamHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/teams')]
class TeamWebController extends AbstractController
{

    #[Route('/new', name: 'player_web_create', methods: ['GET', 'POST'])]
    public function createTeam(Request $request, CreateTeamHandler $handler): Response
    {
        $success = null;
        $error = null;

        if ($request->isMethod('POST')) {
            $firstName = $request->request->get('firstName');
            $lastName = $request->request->get('lastName');
            $email = $request->request->get('email');
            $age = (int) $request->request->get('age');
            $position = Position::from($request->request->get('position'));

            try {
                $playerId = $handler->handle(new RegisterPlayerCommand(
                    firstName: $firstName,
                    lastName: $lastName,
                    age: $age,
                    email: $email,
                    position: $position,
                ));
                $success = $playerId->toString();
            } catch (DomainException $e) {
                $error = $e->getMessage();
            }
        }

        return $this->render('team/create.html.twig', [

        ]);
    }
}
