<?php

namespace App\Match\Infrastructure\EventListener;

use App\Match\Domain\Event\PlayerSummonedEvent;
use App\Match\Domain\Repository\MatchRepositoryInterface;
use App\Player\Domain\Repository\PlayerRepositoryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;

final class SendSummonNotificationEmailListener implements EventSubscriberInterface
{
    public function __construct(
        private readonly MatchRepositoryInterface $matches,
        private readonly PlayerRepositoryInterface $players,
        private readonly MailerInterface $mailer,
    ) {}

    public static function getSubscribedEvents(): array
    {
        return [PlayerSummonedEvent::class => 'onPlayerSummoned'];
    }

    public function onPlayerSummoned(PlayerSummonedEvent $event): void
    {
        $match = $this->matches->findById($event->matchId);
        $player = $this->players->findById($event->playerId);

        if ($match === null || $player === null) {
            return;
        }

        $email = (new TemplatedEmail())
            ->from(new Address('no-reply@cav.fr', 'CA Venette'))
            ->to(new Address($player->getEmail()->toString(), $player->getFirstName() . ' ' . $player->getLastName()))
            ->subject('Convocation - ' . $match->getHomeClub()->getName() . ' vs ' . $match->getAwayClub()->getName())
            ->htmlTemplate('emails/player_summoned.html.twig')
            ->context([
                'player' => $player,
                'match' => $match,
            ]);

        $this->mailer->send($email);
    }
}
