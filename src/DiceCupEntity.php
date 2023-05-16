<?php

declare(strict_types=1);

namespace Madmaxi\Farkle;

class DiceCupEntity
{
//    const DICE_1 = 0;
//    const DICE_2 = 1;
//    const DICE_3 = 2;
//    const DICE_4 = 3;
//    const DICE_5 = 4;
    public const DICE_1 = 'dice1';
    public const DICE_2 = 'dice2';
    public const DICE_3 = 'dice3';
    public const DICE_4 = 'dice4';
    public const DICE_5 = 'dice5';
    public const DICE_6 = 'dice6';

    private ?int $dice1 = null;
    private ?int $dice2 = null;
    private ?int $dice3 = null;
    private ?int $dice4 = null;
    private ?int $dice5 = null;
    private ?int $dice6 = null;
    private int $tmpPoints = 0;
    private int $points = 0;

    public function getPoints(): int
    {
        return $this->points;
    }

    public function setPoints(int $points): self
    {
        $this->points = $points;
        return $this;
    }

    public function getDice1(): ?int
    {
        return $this->dice1;
    }

    public function setDice1(?int $dice1): self
    {
        $this->dice1 = $dice1;
        return $this;
    }

    public function getDice2(): ?int
    {
        return $this->dice2;
    }

    public function setDice2(?int $dice2): self
    {
        $this->dice2 = $dice2;
        return $this;
    }

    public function getDice3(): ?int
    {
        return $this->dice3;
    }

    public function setDice3(?int $dice3): self
    {
        $this->dice3 = $dice3;
        return $this;
    }

    public function getDice4(): ?int
    {
        return $this->dice4;
    }

    public function setDice4(?int $dice4): self
    {
        $this->dice4 = $dice4;
        return $this;
    }

    public function getDice5(): ?int
    {
        return $this->dice5;
    }

    public function setDice5(?int $dice5): self
    {
        $this->dice5 = $dice5;
        return $this;
    }

    public function getDice6(): ?int
    {
        return $this->dice6;
    }

    public function setDice6(?int $dice6): self
    {
        $this->dice6 = $dice6;
        return $this;
    }

    public function getTmpPoints(): int
    {
        return $this->tmpPoints;
    }

    public function setTmpPoints(int $tmpPoints): self
    {
        $this->tmpPoints = $tmpPoints;
        return $this;
    }

    public function getValuesAsArray(): array
    {
        return array_filter(
            [
                self::DICE_1 => $this->dice1,
                self::DICE_2 => $this->dice2,
                self::DICE_3 => $this->dice3,
                self::DICE_4 => $this->dice4,
                self::DICE_5 => $this->dice5,
                self::DICE_6 => $this->dice6,
            ]
        );
    }

    public function setAside(string $dice): void
    {
        match ($dice) {
            self::DICE_1 => $this->dice1 = null,
            self::DICE_2 => $this->dice2 = null,
            self::DICE_3 => $this->dice3 = null,
            self::DICE_4 => $this->dice4 = null,
            self::DICE_5 => $this->dice5 = null,
            self::DICE_6 => $this->dice6 = null,
            default => throw new \InvalidArgumentException('wrong dice ' . $dice),
        };
    }

    public function addTmpPoints(int $points): void
    {
        $this->tmpPoints += $points;
    }

    public function setAllNull(): void
    {
        $this->dice1 = null;
        $this->dice2 = null;
        $this->dice3 = null;
        $this->dice4 = null;
        $this->dice5 = null;
        $this->dice6 = null;
    }

    public function softResetCup(): self
    {
        $this->tmpPoints = 0;
        $this->setAllNull();
        return $this;
    }
}
