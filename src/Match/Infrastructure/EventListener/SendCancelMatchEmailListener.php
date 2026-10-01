<?php

namespace App\Match\Infrastructure\EventListener;

use App\Match\Domain\Event\MatchCancelledEvent;
use App\Match\Domain\Repository\MatchRepositoryInterface;
use App\Player\Domain\Repository\PlayerRepositoryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;

final class SendCancelMatchEmailListener implements EventSubscriberInterface
{
    public function __construct(
        private readonly MatchRepositoryInterface $matches,
        private readonly PlayerRepositoryInterface $players,
        private readonly MailerInterface $mailer,
    ) {}

    public static function getSubscribedEvents(): array
    {
        return [MatchCancelledEvent::class => 'onMatchCancelled'];
    }

    public function onMatchCancelled(MatchCancelledEvent $event): void
    {
        $match = $this->matches->findById($event->matchId);
        if ($match === null) {
            return;
        }

        foreach ($match->getSummonedPlayers() as $playerId) {
            $player = $this->players->findById($playerId);
            if ($player === null) {
                continue;
            }

            $email = (new TemplatedEmail())
                ->from(new Address('no-reply@ca-venette.fr', 'CA Venette'))
                ->to(new Address($player->getEmail()->toString(), $player->getFirstName() . ' ' . $player->getLastName()))
                ->subject('Match annulé - ' . $match->getHomeClub()->getName() . ' vs ' . $match->getAwayClub()->getName())
                ->htmlTemplate('emails/match_cancelled.html.twig')
                ->context([
                    'player' => $player,
                    'match' => $match,
                ]);

            $this->mailer->send($email);
        }
    }
}
