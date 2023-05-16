<?php

declare(strict_types=1);

namespace unit;

use Madmaxi\Farkle\DiceCupEntity;
use Madmaxi\Farkle\PointsService;
use Madmaxi\Farkle\RoundService;
use Madmaxi\Farkle\Service\ThrowService;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class RoundServiceTest extends TestCase
{
    use ProphecyTrait;

    private readonly RoundService $roundService;

    protected function setUp(): void
    {
        $pointsService = new PointsService();
        $throwService = new ThrowService();
        $this->roundService = new RoundService($pointsService, $throwService);
    }

    public function testDoRound()
    {
        $cupEntity = new DiceCupEntity();

        $result = $this->roundService->doARound($cupEntity);

        $this->assertIsInt($result);
        $this->assertEquals(true, $this->assertValueIsValid($result));
    }

    private function assertValueIsValid(int $value): bool
    {
        if ($value === 0) {
            return true;
        }
        if ($value % 50 === 0) {
            return true;
        }
        return false;
    }
}
