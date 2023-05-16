<?php

declare(strict_types=1);

namespace Madmaxi\Farkle;

use Madmaxi\Farkle\Service\ThrowService;

class RoundService
{
    public function __construct(
        private readonly PointsService $pointsService,
        private readonly ThrowService $throwService,
    ) {
    }

    /*
     * Point Threshold
     * Dice left
     * Ignore triple 2
     * Ignore 50
     */

    public function doARound(DiceCupEntity $cupEntity): int
    {
        $anotherThrow = true;
        while ($anotherThrow) {
            $prePoints = $cupEntity->getTmpPoints();
            $this->throwService->throwCup($cupEntity);
            $cupEntity = $this->pointsService->calculatePoints($cupEntity);
            $anotherThrow = $this->throwService->anotherThrow($cupEntity, $prePoints);
        }

        $points = 0;
        if ($cupEntity->getTmpPoints() >= ThrowService::MINIMUM_POINTS) {
            $points = $cupEntity->getTmpPoints();
            $cupEntity->setPoints($points);
        }
        $cupEntity->softResetCup();

        return $points;
    }
}
