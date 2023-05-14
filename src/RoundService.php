<?php

declare(strict_types=1);

namespace Madmaxi\Farkle;

class RoundService
{
    const THRESHOLD_POINTS = 600;
    const DICE_LEFT = 1;
    const MINIMUM_POINTS = 400;
    const MINIMUM_DICE_LEFT = 3;

    public function throwCup(DiceCupEntity $cupEntity)
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

        if (!empty($cupEntity->getDice1())) {
            $cupEntity->setDice1($this->throw());
        }
        if (!empty($cupEntity->getDice2())) {
            $cupEntity->setDice2($this->throw());
        }
        if (!empty($cupEntity->getDice3())) {
            $cupEntity->setDice3($this->throw());
        }
        if (!empty($cupEntity->getDice4())) {
            $cupEntity->setDice4($this->throw());
        }
        if (!empty($cupEntity->getDice5())) {
            $cupEntity->setDice5($this->throw());
        }
        if (!empty($cupEntity->getDice6())) {
            $cupEntity->setDice6($this->throw());
        }

        return $cupEntity;
    }

    private function throw()
    {
        return random_int(1, 6);
    }

    public function anotherThrow(DiceCupEntity $cupEntity): bool
    {
        $tmpPoints = $cupEntity->getTmpPoints();
        $amountDice = count($cupEntity->getValuesAsArray());
        if ($amountDice === 0) {
            return true;
        }

        if ($amountDice <= self::DICE_LEFT &&
            $tmpPoints >= self::MINIMUM_POINTS) {
            $cupEntity->setPoints($tmpPoints);
            $cupEntity->setTmpPoints(0);
            $cupEntity->setAllNull();
            return false;
        }

        if ($tmpPoints >= self::THRESHOLD_POINTS &&
            $amountDice >= self::MINIMUM_DICE_LEFT) {
            $cupEntity->setPoints($tmpPoints);
            $cupEntity->setTmpPoints(0);
            $cupEntity->setAllNull();
            return false;
        }

        return true;
    }
}