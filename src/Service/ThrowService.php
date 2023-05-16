<?php

declare(strict_types=1);

namespace Madmaxi\Farkle\Service;

use Madmaxi\Farkle\DiceCupEntity;

class ThrowService
{
    public const THRESHOLD_POINTS = 600;
    public const DICE_LEFT = 1;
    public const MINIMUM_POINTS = 400;
    public const MINIMUM_DICE_LEFT = 3;

    public function throwCup(DiceCupEntity $cupEntity): DiceCupEntity
    {
        $count = count($cupEntity->getValuesAsArray());
        if ($count === 0) {
            $cupEntity->setDice1($this->throw());
            $cupEntity->setDice2($this->throw());
            $cupEntity->setDice3($this->throw());
            $cupEntity->setDice4($this->throw());
            $cupEntity->setDice5($this->throw());
            $cupEntity->setDice6($this->throw());
            return $cupEntity;
        }

        if (! empty($cupEntity->getDice1())) {
            $cupEntity->setDice1($this->throw());
        }
        if (! empty($cupEntity->getDice2())) {
            $cupEntity->setDice2($this->throw());
        }
        if (! empty($cupEntity->getDice3())) {
            $cupEntity->setDice3($this->throw());
        }
        if (! empty($cupEntity->getDice4())) {
            $cupEntity->setDice4($this->throw());
        }
        if (! empty($cupEntity->getDice5())) {
            $cupEntity->setDice5($this->throw());
        }
        if (! empty($cupEntity->getDice6())) {
            $cupEntity->setDice6($this->throw());
        }

        return $cupEntity;
    }

    private function throw(): int
    {
        return random_int(1, 6);
    }

    public function anotherThrow(DiceCupEntity $cupEntity,int $prePoints): bool
    {
        $tmpPoints = $cupEntity->getTmpPoints();
        if ($tmpPoints - $prePoints === 0) {
            return false;
        }

        $amountDice = count($cupEntity->getValuesAsArray());
        if ($amountDice === 0) {
            return true;
        }

        if ($amountDice <= self::DICE_LEFT &&
            $tmpPoints >= self::MINIMUM_POINTS) {
            return false;
        }

        if ($tmpPoints >= self::THRESHOLD_POINTS &&
            $amountDice >= self::MINIMUM_DICE_LEFT) {
            return false;
        }

        return true;
    }
}