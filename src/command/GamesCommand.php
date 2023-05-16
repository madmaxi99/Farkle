<?php

declare(strict_types=1);

namespace Madmaxi\Farkle\command;

use Madmaxi\Farkle\DiceCupEntity;
use Madmaxi\Farkle\PointsService;
use Madmaxi\Farkle\RoundService;
use Madmaxi\Farkle\Service\ProgressbarService;
use Madmaxi\Farkle\TableService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
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
        $progressBar = ProgressbarService::getProgressbar($output);

        $totalRounds = 0;
        $tp = 0;
        $pointsArray = [];
        for ($i = 0; $i < $games; $i++) {
            $totalPoints = 0;
            $rounds = 0;
            $cupEntity = new DiceCupEntity();

            while ($totalPoints <= self::GAME_WINNING_POINTS) {
                $points = $this->roundService->doARound($cupEntity);

                $totalPoints += $points;
                $rounds++;
                $pointsArray = PointsService::pointsArray($pointsArray, $points);
            }
            $tp += $totalPoints;
            $totalRounds += $rounds;

            ProgressbarService::nextRound($games, $i, $progressBar);
        }
        $progressBar->finish();
        $output->writeln('');

        $table = TableService::getTable($output, ['Points', 'Amount']);
        $zeroGames = 0;
        ksort($pointsArray);
        foreach ($pointsArray as $points => $amount) {
            if ($points === 0) {
                $zeroGames = $amount;
            }
            $table->addRow([$points, $amount]);
        }
        $table->render();

        $conclusion = sprintf(
            '%s Total Rounds: %s, Avg. Rounds: %s, Avg. Points: %s, ZeroGames: %d%%',
            PHP_EOL,
            $totalRounds,
            $totalRounds / $games,
            $tp / $totalRounds,
            ($zeroGames / $totalRounds) * 100,
        );
        $output->writeln($conclusion);
        return self::SUCCESS;
    }
}
