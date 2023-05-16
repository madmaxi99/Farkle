<?php

declare(strict_types=1);

namespace Madmaxi\Farkle\Service;

use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\OutputInterface;

class ProgressbarService
{
    public static function getProgressbar(OutputInterface $output, int $limit = 100): ProgressBar
    {
        $progressBar = new ProgressBar($output, $limit);
        $progressBar->start();
        return $progressBar;
    }

    public static function nextRound(int $rounds, int $i, ProgressBar $progressBar): void
    {
        $percent = round($rounds / 100);
        if ($rounds > 100) {
            if (($i % $percent) === 0) {
                $progressBar->advance();
                $progressBar->display();
            }
        }
    }
}
