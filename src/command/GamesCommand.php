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

            $progressBar->advance();
            $progressBar->display();
        }
        $progressBar->finish();

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
