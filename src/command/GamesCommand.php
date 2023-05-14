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

#[AsCommand(name: 'farkle:games')]
class GamesCommand extends Command
{
    public const GAME_WINNING_POINTS = 10000;

    public function __construct(
        private readonly RoundService $roundService,
    ) {
        parent::__construct();
    }

    protected function configure()
    {
        $this->addArgument('games', InputArgument::REQUIRED, 'NUmber of games');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $games = (int) $input->getArgument('games');
        $progressBar = new ProgressBar($output, $games);
        $progressBar->start();

        $totalRounds = 0;
        $tp = 0;
        $pointsArray = [];
        for ($i = 0; $i < $games; $i++) {
            $totalPoints = 0;
            $rounds = 0;
            $cupEntity = new DiceCupEntity();

            while ($totalPoints <= self::GAME_WINNING_POINTS) {
                $cupEntity = $this->roundService->doARound($cupEntity);

                $totalPoints += $cupEntity->getPoints();
                $rounds++;
            }
            $tp += $totalPoints;
            $totalRounds += $rounds;

            $points = $cupEntity->getPoints();
            if (isset($pointsArray[$points])) {
                $pointsArray[$points] += 1;
            } else {
                $pointsArray[$points] = 1;
            }

            $progressBar->advance();
            $progressBar->display();
        }
        $progressBar->finish();

        $table = TableService::getTable($output);
        ksort($pointsArray);
        foreach ($pointsArray as $points => $amount) {
            $table->addRow([$points, $amount]);
        }
        $table->render();

        $conclusion = sprintf(
            '%s Total Rounds: %s, Avg. Rounds: %s, Avg. Points: %s',
            PHP_EOL,
            $totalRounds,
            $totalRounds / $games,
            $tp / $totalRounds
        );
        $output->writeln($conclusion);
        return self::SUCCESS;
    }
}
