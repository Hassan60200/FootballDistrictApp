<?php

namespace App\Tests\Match\Infrastructure\EventListener;

use App\Match\Domain\ClubInfo;
use App\Match\Domain\Event\PlayerSummonedEvent;
use App\Match\Domain\FootballMatch;
use App\Match\Domain\MatchDateTime;
use App\Match\Domain\Repository\MatchRepositoryInterface;
use App\Match\Infrastructure\EventListener\SendSummonNotificationSmsListener;
use App\Player\Domain\Email;
use App\Player\Domain\PhoneNumber;
use App\Player\Domain\Player;
use App\Player\Domain\Position;
use App\Player\Domain\Repository\PlayerRepositoryInterface;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\Notifier\Exception\RuntimeException;
use Symfony\Component\Notifier\Message\MessageInterface;
use Symfony\Component\Notifier\Message\SmsMessage;
use Symfony\Component\Notifier\TexterInterface;

final class SendSummonNotificationSmsListenerTest extends TestCase
{
    public function testSendsSmsToPlayerPhoneNumberThroughTwilioSmsTransport(): void
    {
        $sms = $this->sentSms($this->aMatch(), $this->aPlayer(phone: '06 12 34 56 78'));

        $this->assertSame('+33612345678', $sms->getPhone());
        $this->assertSame('twilio_sms', $sms->getTransport());
    }

    public function testMessageGreetsPlayerAndDescribesMatch(): void
    {
        $text = $this->sentSms($this->aMatch(), $this->aPlayer(firstName: 'Karim'))->getSubject();

        $this->assertStringContainsString('Salut Karim', $text);
        $this->assertStringContainsString('Seniors A vs Canly', $text);
        $this->assertStringContainsString('21/10 a 15:00', $text);
    }

    public function testMessageIsPlainAsciiToStayInGsmEncoding(): void
    {
        $text = $this->sentSms(
            $this->aMatch(home: 'Étoile FC'),
            $this->aPlayer(firstName: 'Raphaël'),
        )->getSubject();

        $this->assertStringContainsString('Salut Raphael', $text);
        $this->assertStringContainsString('Etoile FC', $text);
        $this->assertDoesNotMatchRegularExpression('/[^\x20-\x7E]/', $text);
    }

    public function testSendsNothingWhenMatchIsNotFound(): void
    {
        $match = $this->aMatch();
        $player = $this->aPlayer();

        $texter = $this->createMock(TexterInterface::class);
        $texter->expects($this->never())->method('send');

        $this->listener(null, $player, $texter)
            ->onPlayerSummoned(new PlayerSummonedEvent($match->getId(), $player->getId()));
    }

    public function testSendsNothingWhenPlayerIsNotFound(): void
    {
        $match = $this->aMatch();
        $player = $this->aPlayer();

        $texter = $this->createMock(TexterInterface::class);
        $texter->expects($this->never())->method('send');

        $this->listener($match, null, $texter)
            ->onPlayerSummoned(new PlayerSummonedEvent($match->getId(), $player->getId()));
    }

    public function testLogsAndDoesNotThrowWhenTexterFails(): void
    {
        $match = $this->aMatch();
        $player = $this->aPlayer();

        $texter = $this->createStub(TexterInterface::class);
        $texter->method('send')->willThrowException(new RuntimeException('Twilio down'));

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())
            ->method('error')
            ->with($this->stringContains('Twilio down'));

        $this->listener($match, $player, $texter, $logger)
            ->onPlayerSummoned(new PlayerSummonedEvent($match->getId(), $player->getId()));
    }

    public function testIsSubscribedToPlayerSummonedEvent(): void
    {
        $this->assertArrayHasKey(
            PlayerSummonedEvent::class,
            SendSummonNotificationSmsListener::getSubscribedEvents(),
        );
    }

    private function sentSms(FootballMatch $match, Player $player): SmsMessage
    {
        $sent = null;

        $texter = $this->createMock(TexterInterface::class);
        $texter->expects($this->once())
            ->method('send')
            ->willReturnCallback(function (MessageInterface $message) use (&$sent) {
                $sent = $message;

                return null;
            });

        $this->listener($match, $player, $texter)
            ->onPlayerSummoned(new PlayerSummonedEvent($match->getId(), $player->getId()));

        $this->assertInstanceOf(SmsMessage::class, $sent);

        return $sent;
    }

    private function listener(
        ?FootballMatch $match,
        ?Player $player,
        TexterInterface $texter,
        ?LoggerInterface $logger = null,
    ): SendSummonNotificationSmsListener {
        $matches = $this->createStub(MatchRepositoryInterface::class);
        $matches->method('findById')->willReturn($match);

        $players = $this->createStub(PlayerRepositoryInterface::class);
        $players->method('findById')->willReturn($player);

        return new SendSummonNotificationSmsListener(
            $matches,
            $players,
            $texter,
            $logger ?? $this->createStub(LoggerInterface::class),
        );
    }

    private function aMatch(string $home = 'Seniors A', string $away = 'Canly'): FootballMatch
    {
        return FootballMatch::schedule(
            externalId: 'manual_test',
            scheduledAt: MatchDateTime::fromDateAndTime(new \DateTimeImmutable('2026-10-21'), '15H00'),
            competitionName: 'Seniors D5',
            homeClub: new ClubInfo($home, '', null),
            awayClub: new ClubInfo($away, '', null),
        );
    }

    private function aPlayer(string $firstName = 'Karim', string $phone = '0612345678'): Player
    {
        return Player::register(
            firstName: $firstName,
            lastName: 'Benzema',
            age: 36,
            email: Email::fromString('karim@ca-venette.fr'),
            position: Position::FORWARD,
            phoneNumber: PhoneNumber::fromString($phone),
        );
    }
}
