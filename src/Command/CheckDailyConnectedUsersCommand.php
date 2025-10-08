<?php

namespace App\Command;

use App\Repository\UserRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'command:app:check-daily-connected-users',
    description: 'get the number of users connected today',
)]
class CheckDailyConnectedUsersCommand extends Command
{
    public function __construct(UserRepository $userRepository)
    {
        parent::__construct();
        $this->userRepository = $userRepository;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $today = new \DateTime('today');

        $qb = $this->userRepository->createQueryBuilder('u')
            ->where('u.updatedAt >= :today')
            ->setParameter('today', $today);

        $count = count($qb->getQuery()->getResult());

        $output->writeln(sprintf('Nombre d\'utilisateurs connectés aujourd\'hui : %d', $count));

        return Command::SUCCESS;
    }
}
