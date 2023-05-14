<?php

declare(strict_types=1);

namespace Madmaxi\Farkle;

class PointsService
{

    const SMALL_FLUSH_A = [1, 2, 3, 4, 5];
    const SMALL_FLUSH_B = [2, 3, 4, 5, 6];
    const BIG_FLUSH = [1, 2, 3, 4, 5, 6];
    const FACTOR_100 = 100;
    const FACTOR_50 = 50;

    public function calculatePoints(DiceCupEntity $cupEntity): int
    {
        $valuesAsArray = $cupEntity->getValuesAsArray();
        $points = $cupEntity->getTmpPoints();
        $this->detectFlush($cupEntity);
        $this->detectTriplePairs($cupEntity);
        $this->detectMultiple($cupEntity);
        $this->detectSingle($cupEntity);

        return $cupEntity->getTmpPoints() - $points;
    }

    public function detectFlush(DiceCupEntity $cupEntity): DiceCupEntity
    {
        $diceValues = $cupEntity->getValuesAsArray();
        asort($diceValues);

        $filterValues = array_values(array_unique($diceValues));

        if (count($filterValues) === 6) {
            $cupEntity->setAllNull();
            $cupEntity->addTmpPoints(1500);
        }

        if ($filterValues === self::SMALL_FLUSH_A) {
            $bla = 1;
            foreach ($diceValues as $dice => $diceValue) {
                if ($diceValue === $bla) {
                    $cupEntity->{'set' . ($dice)}(null);
                    $bla++;
                }
            }
            $cupEntity->addTmpPoints(1000);
        }

        if ($filterValues === self::SMALL_FLUSH_B) {
            $bla = 2;
            foreach ($diceValues as $dice => $diceValue) {
                if ($diceValue === $bla) {
                    $cupEntity->{'set' . ($dice)}(null);
                    $bla++;
                }
            }
            $cupEntity->addTmpPoints(1000);
        }

        return $cupEntity;
    }

    public function invertArray(array $dices): array
    {
        $count = [];
        foreach ($dices as $dice) {
            if (!isset($count[$dice])) {
                $count[$dice] = 1;
            } else {
                $count[$dice]++;
            }
        }
        ksort($count);
        return $count;
    }

    public function detectTriplePairs(DiceCupEntity $cupEntity): DiceCupEntity
    {
        $diceValues = $this->invertArray($cupEntity->getValuesAsArray());
        $pairs = 0;
        foreach ($diceValues as $amount) {
            if ($amount === 2) {
                $pairs++;
            }
        }
        if ($pairs === 3) {
            $cupEntity->addTmpPoints(1500);
            $cupEntity->setAllNull();
        }
        return $cupEntity;
    }

    public function detectMultiple(DiceCupEntity $cupEntity): DiceCupEntity
    {
        $diceValues = $this->invertArray($cupEntity->getValuesAsArray());
        foreach ($diceValues as $eye => $amount) {
            if ($eye === 1) {
                $eye = 10;
            }
            if ($amount === 6) {
                $cupEntity->addTmpPoints($eye * self::FACTOR_100 + 3000);
                $cupEntity->setAllNull();
                break;
            }
            if ($amount === 5) {
                $cupEntity->addTmpPoints($eye * self::FACTOR_100 + 2000);
                $this->setEyeNull($eye, $cupEntity);
                break;
            }
            if ($amount === 4) {
                $cupEntity->addTmpPoints($eye * self::FACTOR_100 + 1000);
                $this->setEyeNull($eye, $cupEntity);
                break;
            }
            if ($amount === 3) {
                $cupEntity->addTmpPoints($eye * self::FACTOR_100);
                $this->setEyeNull($eye, $cupEntity);
            }
        }

        return $cupEntity;
    }

    public function detectSingle(DiceCupEntity $cupEntity): DiceCupEntity
    {
        $diceValues = $cupEntity->getValuesAsArray();
        $diceValues = $this->invertArray($diceValues);

        foreach ($diceValues as $eye => $amount) {
            if ($eye === 1) {
                $cupEntity->addTmpPoints($amount * self::FACTOR_100);
                $this->setEyeNull($eye, $cupEntity);
            }
            if ($eye === 5 && $cupEntity->getTmpPoints() < 100) {
                $cupEntity->addTmpPoints(self::FACTOR_50);
                foreach ($diceValues as $dice => $value) {
                    if ($value === 5) {
                        $cupEntity->{'set' . ($dice)}(null);
                        break;
                    }
                }
            }
        }
        return $cupEntity;
    }

    private function setEyeNull(int $eye, DiceCupEntity $cupEntity): void
    {
        $diceValues = $cupEntity->getValuesAsArray();
        foreach ($diceValues as $dice => $diceValue) {
            if ($eye === 10) {
                $eye = 1;
            }
            if ($diceValue === $eye) {
                $cupEntity->{'set' . ($dice)}(null);
            }
        }
    }
}