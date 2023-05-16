<?php

declare(strict_types=1);

namespace Madmaxi\Farkle\command;

use Madmaxi\Farkle\DiceCupEntity;
use Madmaxi\Farkle\PointsService;
use Madmaxi\Farkle\Service\ProgressbarService;
use Madmaxi\Farkle\Service\ThrowService;
use Madmaxi\Farkle\TableService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'farkle:throws')]
class ThrowCommand extends Command
{
    public function __construct(
        private readonly ThrowService $throwService,
    ) {
        parent::__construct();
    }

    protected function configure()
    {
        $this->addArgument('throws', InputArgument::REQUIRED, 'NUmber of throws');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $throws = (int) $input->getArgument('throws');
        $progressBar = ProgressbarService::getProgressbar($output);

        $totalThrown = [
            1 => 0,
            2 => 0,
            3 => 0,
            4 => 0,
            5 => 0,
            6 => 0,
        ];

        for ($i = 0; $i < $throws; $i++) {
            $cupEntity = new DiceCupEntity();

            $this->throwService->throwCup($cupEntity);

            $diceValues = PointsService::invertArray($cupEntity->getValuesAsArray());
            foreach ($diceValues as $eye => $amount) {
                $totalThrown[$eye] += $amount;
            }

            ProgressbarService::nextRound($throws, $i, $progressBar);
        }
        $progressBar->finish();
        $output->writeln(PHP_EOL);

        $table = TableService::getTable($output, [1, 2, 3, 4, 5, 6]);
        $table->addRow($totalThrown);
        $table->render();

        $conclusion = sprintf('%s', PHP_EOL);
        $output->writeln($conclusion);
        return self::SUCCESS;
    }
}
