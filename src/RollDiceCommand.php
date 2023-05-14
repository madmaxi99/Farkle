<?php

declare(strict_types=1);

namespace Madmaxi\Farkle;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'game:farkle')]
class RollDiceCommand extends Command
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
    public function doARound(): DiceCupEntity
    {
        $cupEntity = new DiceCupEntity();
        $canThrow = true;
        while ($canThrow) {
            $this->roundService->throwCup($cupEntity);
            $pointsDiff = $this->pointsService->calculatePoints($cupEntity);
            dump([$pointsDiff, $cupEntity->getTmpPoints()]);
            if ($pointsDiff !== 0)
            {
                $canThrow = $this->roundService->anotherRound($pointsDiff, $cupEntity);
            } else {
                $canThrow = false;
            }
        }
        return $cupEntity;
    }

    protected function configure()
    {
//        $this->addArgument('rounds', InputArgument::REQUIRED, 'NUmber of Rounds');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
//        $rounds = (int)$input->getArgument('rounds');
//        $progressBar = new  ProgressBar($output, $rounds);
//        $progressBar->start();
        $games = 1;
        $pointsArray = [];
        $totalPoints = 0;
        $rounds = 0;

        while ($totalPoints < self::GAME_WINNING_POINTS) {
            $cupEntity = $this->doARound();
            $totalPoints += $cupEntity->getPoints();
            $rounds++;
        }

        $pointsArray[] = $cupEntity->getPoints();

        $pointsStats = $this->pointsService->invertArray($pointsArray);

        $avgPoints = $totalPoints / $rounds;
        $avgRounds = $rounds / $games;
        $conclusion = sprintf('%s Avg: %d, Total Points: %d, Avg Rounds: %d', PHP_EOL, $avgPoints, $totalPoints, $avgRounds);
        $output->writeln($conclusion);
        return self::SUCCESS;
    }
}