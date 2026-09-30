<?php

namespace App\Match\Infrastructure\EventListener;

use App\Match\Domain\Event\PlayerSummonedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class SendSummonNotificationEmailListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [PlayerSummonedEvent::class => 'onPlayerSummoned'];
    }

    public function onPlayerSummoned(PlayerSummonedEvent $event): void
    {
        // l'envoi d'email arrive dans la prochaine étape
    }
}
