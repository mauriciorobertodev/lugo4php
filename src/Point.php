<?php

namespace Lugo4php;

use Lugo\Point as LugoPoint;
use Lugo\Vector as LugoVector;

class Point
{
    private float $x;
    private float $y;

    public function __construct(float $x = 0, float $y = 0)
    {
        $this->x = $x;
        $this->y = $y;
    }

    public function getX(): float
    {
        return $this->x;
    }

    public function getY(): float
    {
        return $this->y;
    }

    public function setX(float $x): self
    {
        $this->x = $x;
        return $this;
    }

    public function setY(float $y): self
    {
        $this->y = $y;
        return $this;
    }

	public function toLugoPoint(): LugoPoint
	{
		return (new LugoPoint())->setX($this->x)->setY($this->y);
	}

	public function toLugoVector(): LugoVector
	{
		return (new LugoVector())->setX($this->x)->setY($this->y);
	}

    public function __toString(): string {
        return "[{$this->x}, {$this->y}]";
    }

	public static function fromLugoPoint(LugoPoint $lugoPoint): Point {
		return new Point($lugoPoint->getX(), $lugoPoint->getY());
	}
	
	public static function fromLugoVector(LugoVector $lugoVector): Point {
		return new Point($lugoVector->getX(), $lugoVector->getY());
	}
}