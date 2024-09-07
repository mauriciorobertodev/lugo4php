<?php

namespace Lugo4php\Interfaces;

use Lugo4php\FormationType;
use Lugo4php\Point;

interface IFormation
{
	public function getName(): string;
	public function setName(string $name): self;

	public function getType(): FormationType;
	public function setType(FormationType $type): self;

    public function getPositionOf(int $playerNumber): Point;
    public function setPositionOf(int $playerNumber, Point $position): self;
    public function definePositionOf(int $playerNumber, float $x, float $y): self;

    public function getPositionOf01(): Point;
    public function setPositionOf01(Point $position): self;
    public function definePositionOf01(float $x, float $y): self;

    public function getPositionOf02(): Point;
    public function setPositionOf02(Point $position): self;
    public function definePositionOf02(float $x, float $y): self;

    public function getPositionOf03(): Point;
    public function setPositionOf03(Point $position): self;
    public function definePositionOf03(float $x, float $y): self;

    public function getPositionOf04(): Point;
    public function setPositionOf04(Point $position): self;
    public function definePositionOf04(float $x, float $y): self;

    public function getPositionOf05(): Point;
    public function setPositionOf05(Point $position): self;
    public function definePositionOf05(float $x, float $y): self;

    public function getPositionOf06(): Point;
    public function setPositionOf06(Point $position): self;
    public function definePositionOf06(float $x, float $y): self;

    public function getPositionOf07(): Point;
    public function setPositionOf07(Point $position): self;
    public function definePositionOf07(float $x, float $y): self;

    public function getPositionOf08(): Point;
    public function setPositionOf08(Point $position): self;
    public function definePositionOf08(float $x, float $y): self;

    public function getPositionOf09(): Point;
    public function setPositionOf09(Point $position): self;
    public function definePositionOf09(float $x, float $y): self;

    public function getPositionOf10(): Point;
    public function setPositionOf10(Point $position): self;
    public function definePositionOf10(float $x, float $y): self;

    public function getPositionOf11(): Point;
    public function setPositionOf11(Point $position): self;
    public function definePositionOf11(float $x, float $y): self;

    public function toArray(): array;

    public static function createZeroed(): self;
}
