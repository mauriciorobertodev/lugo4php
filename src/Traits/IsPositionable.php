<?php

namespace Lugo4php\Traits;

use Lugo4php\Interfaces\IPositionable;
use Lugo4php\Point;
use Lugo4php\Vector2D;
use Lugo\Point as LugoPoint;
use Lugo\Vector as LugoVector;

trait IsPositionable {
    private float $x = 0.0;
    private float $y = 0.0;

    public function getX(): float {
        return $this->x;
    }

    public function setX(float $x): self {
        $this->x = round($x, 10);
        return $this;
    }

    public function addX(float $value): self {
       $this->setX($this->x + $value);
        return $this;
    }

    public function subtractX(float $value): self {
        $this->setX($this->x - $value);
        return $this;
    }

    public function scaleX(float $value): self {
        $this->setX($this->x * $value);
        return $this;
    }

    public function divideX(float $value): self {
        if ($value !== 0) {
            $this->setX($this->x / $value);
        }
        return $this;
    }

    public function getY(): float {
        return $this->y;
    }

    public function setY(float $y): self {
        $this->y = round($y, 10);
        return $this;
    }

    public function addY(float $value): self {
        $this->setY($this->y + $value);
        return $this;
    }

    public function subtractY(float $value): self {
        $this->setY($this->y - $value);
        return $this;
    }

    public function scaleY(float $value): self {
        $this->setY($this->y * $value);
        return $this;
    }

    public function divideY(float $value): self {
        if ($value !== 0) {
            $this->setY($this->y / $value);
        }
        return $this;
    }

    public function normalize(): self {
        $magnitude = $this->magnitude();
        if ($magnitude > 0) {
            $this->divideX($magnitude);
            $this->divideY($magnitude);
        }
        return $this;
    }

    public function normalized(): IPositionable {
        return $this->clone()->normalize();
    }

    public function add(IPositionable | float $value): self {
        if (is_float($value)) {
            $this->addX($value);
            $this->addY($value);
        } elseif ($value instanceof IPositionable) {
            $this->addX($value->getX());
            $this->addY($value->getY());
        }
        return $this;
    }

    public function added(IPositionable | float $value): IPositionable {
        return $this->clone()->add($value);
    }

    public function subtract(IPositionable | float $value): self {
        if (is_float($value)) {
            $this->subtractX($value);
            $this->subtractY($value);
        } elseif ($value instanceof IPositionable) {
            $this->subtractX($value->getX());
            $this->subtractY($value->getY());
        }
        return $this;
    }

    public function subtracted(IPositionable | float $value): IPositionable {
        return $this->clone()->subtract($value);
    }

    public function divide(IPositionable | float $value): self {
        if (is_float($value)) {
            $this->divideX($value);
            $this->divideY($value);
        } elseif ($value instanceof IPositionable) {
            $this->divideX($value->getX());
            $this->divideY($value->getY());
        }
        return $this;
    }

    public function divided(IPositionable | float $value): IPositionable {
        return $this->clone()->divide($value);
    }

    public function scale(IPositionable | float $value): self {
        if (is_float($value)) {
            $this->scaleX($value);
            $this->scaleY($value);
        } elseif ($value instanceof IPositionable) {
            $this->scaleX($value->getX());
            $this->scaleY($value->getY());
        }
        return $this;
    }

    public function scaled(IPositionable | float $value): IPositionable {
        return $this->clone()->scale($value);
    }

    public function magnitude(): float {
        return sqrt($this->x ** 2 + $this->y ** 2);
    }

    public function clone(): IPositionable {
        $clone = clone $this;
        return $clone;
    }

    public function directionTo(IPositionable $to): Vector2D {
        return $to->subtracted($this)->normalize()->toVector2D();
    }

    public function distanceTo(IPositionable $to): float {
        return $to->subtracted($this)->magnitude();
    }

    public function moveToDirection(Vector2D $direction, float $distance): self {
        return $this->add($direction->normalized()->scale($distance));
    }

    public function movedToDirection(Vector2D $direction, float $distance): IPositionable {
        return $this->added($direction->normalized()->scale($distance));
    }

    public function moveToPoint(Point $point, float $distance): self 
    {    
        return $this->moveToDirection($this->directionTo($point), $distance);
    }

    public function movedToPoint(Point $point, float $distance): IPositionable {
        return $this->movedToDirection($this->directionTo($point), $distance);
    }

    public function toLugoPoint(): LugoPoint {
        return (new LugoPoint())->setX($this->x)->setY($this->y);
    }

    public function toLugoVector(): LugoVector {
        return (new LugoVector())->setX($this->x)->setY($this->y);
    }

    public function __toString(): string {
        return sprintf("(%.2f, %.2f)", $this->x, $this->y);
    }

    public function toVector2D(): Vector2D
    {
        return new Vector2D($this->x, $this->y);
    }
    
    public function toPoint(): Point
    {
        return new Point($this->x, $this->y);
    }
}
