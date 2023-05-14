#!/usr/bin/env php
<?php

declare(strict_types=1);

use Madmaxi\Farkle\PointsService;
use Madmaxi\Farkle\RollDiceCommand;
use Madmaxi\Farkle\RoundService;
use Symfony\Component\Console\Application;

require __DIR__ . '/../vendor/autoload.php';

$app = new Application('Farkle');

$roundService = new RoundService();
$pointsService = new PointsService();
$rollDiceCommand = new RollDiceCommand($roundService, $pointsService);


$app->add($rollDiceCommand);
$app->run();
