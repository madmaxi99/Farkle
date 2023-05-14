<?php

declare(strict_types=1);

namespace Madmaxi\Farkle;

use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\OutputInterface;

class TableService
{
    public static function getTable(OutputInterface $output): Table
    {
        $table = new Table($output);
        $table->setHeaders(['Points', 'Amount']);
        return $table;
}
}