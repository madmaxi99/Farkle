<?php

declare(strict_types=1);

namespace Madmaxi\Farkle\command;

use Madmaxi\Farkle\DiceCupEntity;
use Madmaxi\Farkle\RoundService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'farkle:rounds')]
class RoundsCommand extends Command
{
    public function __construct(
        private readonly RoundService $roundService,
    ) {
        parent::__construct();
    }

    protected function configure()
    {
        $this->addArgument('rounds', InputArgument::REQUIRED, 'NUmber of games');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $rounds = (int) $input->getArgument('rounds');
        $progressBar = new ProgressBar($output, 100);
        $progressBar->start();

        $highestPoints = 0;
        $totalPoints = 0;
        for ($i = 0; $i < $rounds; $i++) {
            $cupEntity = new DiceCupEntity();
            $this->roundService->doARound($cupEntity);
            if ($highestPoints < $cupEntity->getPoints()) {
                $highestPoints = $cupEntity->getPoints();
            }
            $totalPoints += $cupEntity->getPoints();

            if ($rounds > 100) {
                if (($i % ($rounds / 100)) === 0) {
                    $progressBar->advance();
                    $progressBar->display();
                }
            }
        }
        $progressBar->finish();

        $conclusion = sprintf(
            '%s Highest Points: %s, Avg. Points: %s',
            PHP_EOL,
            $highestPoints,
            $totalPoints / $rounds
        );
        $output->writeln($conclusion);
        return self::SUCCESS;
    }
}
