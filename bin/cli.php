#!/usr/bin/env php
<?php

declare(strict_types=1);

use Madmaxi\Farkle\command\GamesCommand;
use Madmaxi\Farkle\command\RoundsCommand;
use Madmaxi\Farkle\PointsService;
use Madmaxi\Farkle\RoundService;
use Symfony\Component\Console\Application;

require __DIR__ . '/../vendor/autoload.php';

$app = new Application('Farkle');

$pointsService = new PointsService();
$roundService = new RoundService($pointsService);
$gamesCommand = new GamesCommand($roundService);
$roundsCommand = new RoundsCommand($roundService);


$app->add($gamesCommand);
$app->add($roundsCommand);
$app->run();
