<?php

namespace App\Match\Infrastructure\EventListener;

use App\Match\Domain\Event\PlayerSummonedEvent;
use App\Match\Domain\Repository\MatchRepositoryInterface;
use App\Player\Domain\Repository\PlayerRepositoryInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Notifier\Exception\ExceptionInterface;
use Symfony\Component\Notifier\Message\SmsMessage;
use Symfony\Component\Notifier\TexterInterface;
use function Symfony\Component\String\u;

final class SendSummonNotificationSmsListener implements EventSubscriberInterface
{
    public function __construct(
        private readonly MatchRepositoryInterface  $matches,
        private readonly PlayerRepositoryInterface $players,
        private readonly TexterInterface           $texter,
        private readonly LoggerInterface           $logger,
    )
    {
    }

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

        $text = sprintf(
            "CA Venette - Salut %s, tu es convoque : %s vs %s le %s. En cas d'empechement, appelle ton coach.",
            $player->getFirstName(),
            $match->getHomeClub()->getName(),
            $match->getAwayClub()->getName(),
            $match->getScheduledAt()->toDateTimeImmutable()->format('d/m \a H:i'),
        );

        $text = u($text)->ascii()->toString();

        try {
            $this->texter->send(
                (new SmsMessage($player->getPhoneNumber()->toString(), $text))->transport('twilio_sms')
            );
        } catch (ExceptionInterface $e) {
            // La convocation est déjà enregistrée : un SMS raté ne doit pas la casser
            $this->logger->error(sprintf('SMS notification failed for player %s: %s', $player->getId()->toString(), $e->getMessage()));
        }
    }
}
