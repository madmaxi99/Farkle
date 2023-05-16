#!/usr/bin/env php
<?php

declare(strict_types=1);

use Madmaxi\Farkle\command\GamesCommand;
use Madmaxi\Farkle\command\RoundsCommand;
use Madmaxi\Farkle\command\ThrowCommand;
use Madmaxi\Farkle\PointsService;
use Madmaxi\Farkle\RoundService;
use Madmaxi\Farkle\Service\ThrowService;
use Symfony\Component\Console\Application;

require __DIR__ . '/../vendor/autoload.php';

$app = new Application('Farkle');

$pointsService = new PointsService();
$throwService = new ThrowService();
$roundService = new RoundService($pointsService, $throwService);

$gamesCommand = new GamesCommand($roundService);
$roundsCommand = new RoundsCommand($roundService);
$throwCommand = new ThrowCommand($throwService);

$app->add($gamesCommand);
$app->add($roundsCommand);
$app->add($throwCommand);
$app->run();
