<?php

declare(strict_types=1);

namespace unit;

use Madmaxi\Farkle\DiceCupEntity;
use Madmaxi\Farkle\PointsService;
use PHPUnit\Framework\TestCase;

class PointsServiceTest extends TestCase
{
    private readonly PointsService $pointsService;

    protected function setUp(): void
    {
        $this->pointsService = new PointsService();
    }

    /**
     * @dataProvider provideRollsForFlush
     */
    public function testCheckFlush(DiceCupEntity $cupEntity, DiceCupEntity $result)
    {
        $this->assertEquals($result, $this->pointsService->detectFlush($cupEntity));
    }

    public function provideRollsForFlush(): array
    {
        return [
            [
                (new DiceCupEntity())->setDice1(3)->setDice2(4)->setDice3(1)->setDice4(2)->setDice5(5)->setDice6(4),
                (new DiceCupEntity())->setDice1(null)->setDice2(null)->setDice3(null)->setDice4(null)->setDice5(
                    null
                )->setDice6(
                    4
                )->setTmpPoints(1000),
            ],
            [
                (new DiceCupEntity())->setDice1(3)->setDice2(3)->setDice3(1)->setDice4(2)->setDice5(5)->setDice6(
                    4
                )->setTmpPoints(
                    500
                ),
                (new DiceCupEntity())->setDice1(null)->setDice2(3)->setDice3(null)->setDice4(null)->setDice5(
                    null
                )->setDice6(
                    null
                )->setTmpPoints(1500),
            ],
            [
                (new DiceCupEntity())->setDice1(3)->setDice2(4)->setDice3(6)->setDice4(2)->setDice5(5)->setDice6(4),
                (new DiceCupEntity())->setDice1(null)->setDice2(null)->setDice3(null)->setDice4(null)->setDice5(
                    null
                )->setDice6(
                    4
                )->setTmpPoints(1000),
            ],
            [
                (new DiceCupEntity())->setDice1(3)->setDice2(4)->setDice3(1)->setDice4(2)->setDice5(5)->setDice6(6),
                (new DiceCupEntity())->setDice1(null)->setDice2(null)->setDice3(null)->setDice4(null)->setDice5(
                    null
                )->setDice6(
                    null
                )->setTmpPoints(1500),
            ],
            [
                (new DiceCupEntity())->setDice1(3)->setDice2(4)->setDice3(1)->setDice4(1)->setDice5(3)->setDice6(6),
                (new DiceCupEntity())->setDice1(3)->setDice2(4)->setDice3(1)->setDice4(1)->setDice5(3)->setDice6(6),
            ],
        ];
    }

    /**
     * @dataProvider provideRollsForTriplePairs
     */
    public function testCheckTriplePairs(DiceCupEntity $cupEntity, DiceCupEntity $result)
    {
        $this->assertEquals($result, $this->pointsService->detectTriplePairs($cupEntity));
    }

    public function provideRollsForTriplePairs(): array
    {
        return [
            [
                (new DiceCupEntity())->setDice1(3)->setDice2(3)->setDice3(4)->setDice4(4)->setDice5(6)->setDice6(6),
                (new DiceCupEntity())->setDice1(null)->setDice2(null)->setDice3(null)->setDice4(null)->setDice5(
                    null
                )->setDice6(
                    null
                )->setTmpPoints(1500),
            ],
            [
                (new DiceCupEntity())->setDice1(4)->setDice2(6)->setDice3(2)->setDice4(4)->setDice5(6)->setDice6(
                    2
                )->setTmpPoints(
                    500
                ),
                (new DiceCupEntity())->setDice1(null)->setDice2(null)->setDice3(null)->setDice4(null)->setDice5(
                    null
                )->setDice6(
                    null
                )->setTmpPoints(2000),
            ],
            [
                (new DiceCupEntity())->setDice1(3)->setDice2(4)->setDice3(2)->setDice4(1)->setDice5(3)->setDice6(6),
                (new DiceCupEntity())->setDice1(3)->setDice2(4)->setDice3(2)->setDice4(1)->setDice5(3)->setDice6(6),
            ],
        ];
    }

    /**
     * @dataProvider provideRollsForMultiple
     */
    public function testCheckMultiple(DiceCupEntity $cupEntity, DiceCupEntity $result)
    {
        $this->assertEquals($result, $this->pointsService->detectMultiple($cupEntity));
    }

    public function provideRollsForMultiple(): array
    {
        return [
            [
                (new DiceCupEntity())->setDice1(1)->setDice2(1)->setDice3(4)->setDice4(6)->setDice5(2)->setDice6(1),
                (new DiceCupEntity())->setDice1(null)->setDice2(null)->setDice3(4)->setDice4(6)->setDice5(2)->setDice6(
                    null
                )->setTmpPoints(1000),
            ],
            [
                (new DiceCupEntity())->setDice1(3)->setDice2(3)->setDice3(3)->setDice4(3)->setDice5(3)->setDice6(1),
                (new DiceCupEntity())->setDice1(null)->setDice2(null)->setDice3(null)->setDice4(null)->setDice5(
                    null
                )->setDice6(
                    1
                )->setTmpPoints(2300),
            ],
            [
                (new DiceCupEntity())->setDice1(1)->setDice2(1)->setDice3(1)->setDice4(1)->setDice5(1)->setDice6(1),
                (new DiceCupEntity())->setDice1(null)->setDice2(null)->setDice3(null)->setDice4(null)->setDice5(
                    null
                )->setDice6(
                    null
                )->setTmpPoints(4000),
            ],
            [
                (new DiceCupEntity())->setDice1(4)->setDice2(4)->setDice3(1)->setDice4(2)->setDice5(6)->setDice6(4),
                (new DiceCupEntity())->setDice1(null)->setDice2(null)->setDice3(1)->setDice4(2)->setDice5(6)->setDice6(
                    null
                )->setTmpPoints(400),
            ],
            [
                (new DiceCupEntity())->setDice1(4)->setDice2(4)->setDice3(3)->setDice4(3)->setDice5(3)->setDice6(4),
                (new DiceCupEntity())->setDice1(null)->setDice2(null)->setDice3(null)->setDice4(null)->setDice5(
                    null
                )->setDice6(
                    null
                )->setTmpPoints(700),
            ],
            [
                (new DiceCupEntity())->setDice1(2)->setDice2(2)->setDice3(2)->setDice4(1)->setDice5(5)->setDice6(
                    2
                )->setTmpPoints(
                    500
                ),
                (new DiceCupEntity())->setDice1(null)->setDice2(null)->setDice3(null)->setDice4(1)->setDice5(
                    5
                )->setDice6(
                    null
                )->setTmpPoints(1700),
            ],
            [
                (new DiceCupEntity())->setDice1(3)->setDice2(4)->setDice3(2)->setDice4(1)->setDice5(3)->setDice6(6),
                (new DiceCupEntity())->setDice1(3)->setDice2(4)->setDice3(2)->setDice4(1)->setDice5(3)->setDice6(6),
            ],
        ];
    }

    /**
     * @dataProvider provideRollsForSingle
     */
    public function testCheckSingle(DiceCupEntity $cupEntity, DiceCupEntity $result)
    {
        $this->assertEquals($result, $this->pointsService->detectSingle($cupEntity,0));
    }

    public function provideRollsForSingle(): array
    {
        return [
            [
                (new DiceCupEntity())->setDice1(3)->setDice2(3)->setDice3(3)->setDice4(3)->setDice5(3)->setDice6(1),
                (new DiceCupEntity())->setDice1(3)->setDice2(3)->setDice3(3)->setDice4(3)->setDice5(3)->setDice6(null)->setTmpPoints(100),
            ],
            [
                (new DiceCupEntity())->setDice1(3)->setDice2(5)->setDice3(3)->setDice4(1)->setDice5(3)->setDice6(1),
                (new DiceCupEntity())->setDice1(3)->setDice2(5)->setDice3(3)->setDice4(null)->setDice5(3)->setDice6(null)->setTmpPoints(200),
            ],
            [
                (new DiceCupEntity())->setDice1(1)->setDice2(3)->setDice3(4)->setDice4(5)->setDice5(3)->setDice6(5)->setTmpPoints(500),
                (new DiceCupEntity())->setDice1(null)->setDice2(3)->setDice3(4)->setDice4(5)->setDice5(3)->setDice6(5)->setTmpPoints(600),
            ],
            [
                (new DiceCupEntity())->setDice1(3)->setDice2(4)->setDice3(3)->setDice4(4)->setDice5(6)->setDice6(6),
                (new DiceCupEntity())->setDice1(3)->setDice2(4)->setDice3(3)->setDice4(4)->setDice5(6)->setDice6(6),
            ],
            [
                (new DiceCupEntity())->setDice1(5)->setDice2(2)->setDice3(5)->setDice4(2)->setDice5(2)->setDice6(6),
                (new DiceCupEntity())->setDice1(null)->setDice2(2)->setDice3(5)->setDice4(2)->setDice5(2)->setDice6(6)->setTmpPoints(50),
            ],
        ];
    }

    /**
     * @dataProvider providePoints
     */
    public function testCalculatePoints(DiceCupEntity $cupEntity, int $tempPoints)
    {
        $cupEntity1 = $this->pointsService->calculatePoints($cupEntity);
        $this->assertEquals($tempPoints, $cupEntity1->getTmpPoints());
    }

    public function providePoints(): array
    {
        return [
            [
                (new DiceCupEntity())->setDice1(6)->setDice2(6)->setDice3(5)->setDice4(6)->setDice5(5)->setDice6(1),
                800
            ],
            [
                (new DiceCupEntity())->setDice1(4)->setDice2(1)->setDice3(4)->setDice4(1)->setDice5(5)->setDice6(4),
                650
            ],
            [
                (new DiceCupEntity())->setDice1(3)->setDice2(1)->setDice3(2)->setDice4(2)->setDice5(6)->setDice6(5),
                100
            ],
            [
                (new DiceCupEntity())->setDice1(3)->setDice2(3)->setDice3(2)->setDice4(2)->setDice5(6)->setDice6(5),
                50
            ],
            [
                (new DiceCupEntity())->setDice1(2)->setDice2(6)->setDice3(4)->setDice4(2)->setDice5(2)->setDice6(1),
                100
            ],
            [
                (new DiceCupEntity())->setDice1(4)->setDice2(null)->setDice3(5)->setDice4(2)->setDice5(null)->setDice6(5)->setTmpPoints(200),
                250
            ],
        ];
    }

    /**
     * @dataProvider provideRollsForTripleTwo
     */
    public function testCheckTripleTwo(DiceCupEntity $cupEntity, DiceCupEntity $result)
    {
        $this->assertEquals($result, $this->pointsService->detectTripleTwo($cupEntity));
    }

    public function provideRollsForTripleTwo(): array
    {
        return [
            [
                (new DiceCupEntity())->setDice1(2)->setDice2(1)->setDice3(2)->setDice4(4)->setDice5(2)->setDice6(1),
                (new DiceCupEntity())->setDice1(null)->setDice2(1)->setDice3(null)->setDice4(4)->setDice5(null)->setDice6(1)->setTmpPoints(200),
            ],
        ];
    }
}
