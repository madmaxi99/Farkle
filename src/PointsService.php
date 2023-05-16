<?php

declare(strict_types=1);

namespace Madmaxi\Farkle;

class PointsService
{
    public const SMALL_FLUSH_A = [1, 2, 3, 4, 5];
    public const SMALL_FLUSH_B = [2, 3, 4, 5, 6];
    public const BIG_FLUSH = [1, 2, 3, 4, 5, 6];
    public const FACTOR_100 = 100;
    public const FACTOR_50 = 50;
    const TRIPLE_TWO_POINTS = 200;

    public function calculatePoints(DiceCupEntity $cupEntity)
    {
        $startPoints = $cupEntity->getTmpPoints();
        $this->detectFlush($cupEntity);
        $this->detectTriplePairs($cupEntity);
        $this->detectMultiple($cupEntity);
        $this->detectSingle($cupEntity, $startPoints);
        if ($startPoints === $cupEntity->getTmpPoints()) {
            $this->detectTripleTwo($cupEntity);
        }
        return $cupEntity;
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
            if ($amount === 3 && $eye !== 2) {
                $cupEntity->addTmpPoints($eye * self::FACTOR_100);
                $this->setEyeNull($eye, $cupEntity);
            }
        }

        return $cupEntity;
    }

    public function detectSingle(DiceCupEntity $cupEntity, int $startPoints): DiceCupEntity
    {
        $valuesAsArray = $cupEntity->getValuesAsArray();
        $diceValues = $this->invertArray($valuesAsArray);

        $allowedKeys = [1, 5];
        $diffKeys = array_diff_key($diceValues, array_flip($allowedKeys));
        $moreThan1And5 = count($diffKeys) > 0;


        foreach ($diceValues as $eye => $amount) {
            if ($eye === 1) {
                $cupEntity->addTmpPoints($amount * self::FACTOR_100);
                $this->setEyeNull($eye, $cupEntity);
            }
            if ($eye === 5) {
                if ($cupEntity->getTmpPoints() === $startPoints && $moreThan1And5) {
                    $cupEntity->addTmpPoints(self::FACTOR_50);
                    foreach ($valuesAsArray as $dice => $value) {
                        if ($value === 5) {
                            $cupEntity->{'set' . ($dice)}(null);
                            break;
                        }
                    }
                }
                if (!$moreThan1And5) {
                    $cupEntity->addTmpPoints($amount * self::FACTOR_50);
                    $this->setEyeNull($eye, $cupEntity);
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

    public function detectTripleTwo(DiceCupEntity $cupEntity): DiceCupEntity
    {
        $diceValues = $this->invertArray($cupEntity->getValuesAsArray());
        foreach ($diceValues as $eye => $amount) {
            if ($eye !== 2) {
                continue;
            }
            if ($amount === 3) {
                $cupEntity->addTmpPoints(self::TRIPLE_TWO_POINTS);
                $this->setEyeNull($eye, $cupEntity);
            }
        }

        return $cupEntity;
    }

    public static function pointsArray(array $pointsArray, $points)
    {
        if (isset($pointsArray[$points])) {
            $pointsArray[$points] += 1;
        } else {
            $pointsArray[$points] = 1;
        }

        return $pointsArray;
    }

    public static function highestPoints(int $highScore, int $newScore)
    {
        if ($highScore < $newScore) {
            $highScore = $newScore;
        }
        return $highScore;
    }
}
