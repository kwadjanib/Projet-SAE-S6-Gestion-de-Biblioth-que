<?php

namespace App\Command;

use App\Repository\ReservationsRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:reservations:expire',
    description: 'Supprime les reservations expirees (J+7).'
)]
class ExpireReservationsCommand extends Command
{
    public function __construct(private ReservationsRepository $reservationsRepository)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $cutoff = (new \DateTimeImmutable())->modify('-7 days');
        $deleted = $this->reservationsRepository->purgeExpired($cutoff);

        $output->writeln(sprintf('Reservations expirees supprimees: %d', $deleted));

        return Command::SUCCESS;
    }
}
