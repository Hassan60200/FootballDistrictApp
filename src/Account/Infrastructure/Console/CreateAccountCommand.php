<?php

declare(strict_types=1);

namespace App\Account\Infrastructure\Console;

use App\Account\Application\Handler\RegisterAccount\RegisterAccountHandler;
use App\Account\Application\RegisterAccount\RegisterAccountCommand;
use App\Account\Domain\ProfileType;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'app:account:create', description: 'Create a login account for an existing Player, Coach or Admin profile')]
final class CreateAccountCommand extends Command
{
    public function __construct(private readonly RegisterAccountHandler $handler)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('email', InputArgument::REQUIRED, 'Login email')
            ->addArgument('profile-type', InputArgument::REQUIRED, 'player | coach | admin')
            ->addArgument('profile-id', InputArgument::REQUIRED, 'Identifier (UUID) of the existing profile');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $profileType = ProfileType::tryFrom((string) $input->getArgument('profile-type'));
        if (null === $profileType) {
            $io->error('profile-type must be one of: player, coach, admin.');

            return Command::INVALID;
        }

        $question = new Question('Password (min. 8 characters): ');
        $question->setHidden(true);
        $question->setHiddenFallback(false);
        $password = $io->askQuestion($question);

        try {
            $accountId = ($this->handler)(new RegisterAccountCommand(
                (string) $input->getArgument('email'),
                (string) $password,
                $profileType,
                (string) $input->getArgument('profile-id'),
            ));
        } catch (\DomainException $e) {
            $io->error($e->getMessage());

            return Command::FAILURE;
        }

        $io->success(sprintf('Account created (%s).', $accountId->toString()));

        return Command::SUCCESS;
    }
}
