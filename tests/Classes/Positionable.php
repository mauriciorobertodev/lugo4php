<?php

namespace Tests\Classes;

use Lugo4php\Interfaces\IPositionable;
use Lugo4php\Traits\IsPositionable;
use Lugo\Point as LugoPoint;
use Lugo\Vector as LugoVector;

class Positionable implements IPositionable{
	use IsPositionable;

    public function __construct(float $x = 0, float $y = 0)
    {
        $this->x = $x;
        $this->y = $y;
    }

    public static function fromLugoPoint(LugoPoint $lugoPoint): Positionable {
        return (new static())->setX($lugoPoint->getX())->setY($lugoPoint->getY());
    }

    public static function fromLugoVector(LugoVector $lugoVector): Positionable {
        return (new static())->setX($lugoVector->getX())->setY($lugoVector->getY());
    }
}