<?php

declare(strict_types=1);

namespace Madmaxi\Farkle\command;

use Madmaxi\Farkle\DiceCupEntity;
use Madmaxi\Farkle\PointsService;
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
    const GAME_WINNING_POINTS = 10000;

    public function __construct(
        private readonly RoundService $roundService,
        private readonly PointsService $pointsService,
    ) {
        parent::__construct();
    }

    /**
     * @return DiceCupEntity
     */
    public function doARound(DiceCupEntity $cupEntity): DiceCupEntity
    {
        $canThrow = true;
        while ($canThrow) {
            $this->roundService->throwCup($cupEntity);
            $pointsDiff = $this->pointsService->calculatePoints($cupEntity);
            if ($pointsDiff !== 0) {
                $canThrow = $this->roundService->anotherRound($cupEntity);
            } else {
                $canThrow = false;
                $cupEntity->setTmpPoints(0);
                $cupEntity->setAllNull();
            }
        }
        return $cupEntity;
    }

    protected function configure()
    {
        $this->addArgument('games', InputArgument::REQUIRED, 'NUmber of games');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $games = (int)$input->getArgument('games');
        $progressBar = new  ProgressBar($output, $games);
        $progressBar->start();

        $totalRounds = 0;
        for ($i = 0; $i < $games; $i++) {
            $totalPoints = 0;
            $rounds = 0;
            $cupEntity = new DiceCupEntity();

            while ($totalPoints <= self::GAME_WINNING_POINTS) {
                $cupEntity = $this->doARound($cupEntity);
                $totalPoints += $cupEntity->getPoints();
                $rounds++;
            }
            $totalRounds += $rounds;

            $progressBar->advance();
            $progressBar->display();
        }
        $progressBar->finish();


        $conclusion = sprintf('%s Total Rounds: %s, Avg. Rounds: %s', PHP_EOL, $totalRounds, $totalRounds/$games);
        $output->writeln($conclusion);
        return self::SUCCESS;
    }
}