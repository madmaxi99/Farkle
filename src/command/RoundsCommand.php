<?php

declare(strict_types=1);

namespace Madmaxi\Farkle\command;

use Madmaxi\Farkle\DiceCupEntity;
use Madmaxi\Farkle\RoundService;
use Madmaxi\Farkle\TableService;
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
        $progressBar = ProgressbarService::getProgressbar($output);

        $highestPoints = 0;
        $totalPoints = 0;
        $pointsArray = [];
        for ($i = 0; $i < $rounds; $i++) {
            $cupEntity = new DiceCupEntity();
            $this->roundService->doARound($cupEntity);

            $points = $cupEntity->getPoints();
            if ($highestPoints < $points) {
                $highestPoints = $points;
            }
            $totalPoints += $points;

            ProgressbarService::nextRound($rounds, $i, $progressBar);
        }
        $progressBar->finish();

        $table = TableService::getTable($output);
        ksort($pointsArray);
        foreach ($pointsArray as $points => $amount) {
            $table->addRow([$points, $amount]);
        }
        $table->render();

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
