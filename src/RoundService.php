<?php

declare(strict_types=1);

namespace Madmaxi\Farkle;

class RoundService
{
    const MINIMUM_POINTS = 600;
    const DICE_LEFT = 2;

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

    public function anotherRound(int $pointsDiff, DiceCupEntity $cupEntity): bool
    {
        if (self::DICE_LEFT >= count($cupEntity->getValuesAsArray())) {
            $cupEntity->setPoints($pointsDiff);
            return false;
        }

        if (self::MINIMUM_POINTS >= $pointsDiff) {
            $cupEntity->setPoints($pointsDiff);
            return false;
        }

        return true;
    }
}