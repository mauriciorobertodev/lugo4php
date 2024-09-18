<?php

namespace Lugo4php\Interfaces;

use Lugo4php\Point;
use Lugo4php\Vector2D;
use Lugo\Point as LugoPoint;
use Lugo\Vector as LugoVector;

interface IPositionable {
    public function getX(): float;
    public function setX(float $x): self;
    public function addX(float $value): self;
    public function subtractX(float $value): self;
    public function scaleX(float $value): self;
    public function divideX(float $value): self;

    public function getY(): float;
    public function setY(float $y): self;
    public function addY(float $value): self;
    public function subtractY(float $value): self;
    public function scaleY(float $value): self;
	public function divideY(float $value): self;

	public function normalize(): self;
	public function normalized(): IPositionable;

	public function add(IPositionable | float $value): self;
	public function added(IPositionable | float $value): IPositionable;

	public function subtract(IPositionable | float $value): self;
	public function subtracted(IPositionable | float $value): IPositionable;

	public function divide(IPositionable | float $value): self;
	public function divided(IPositionable | float $value): IPositionable;

	public function scale(IPositionable | float $value): self;
	public function scaled(IPositionable | float $value): IPositionable;

	public function magnitude(): float;
	public function clone(): IPositionable;
	public function directionTo(IPositionable $to): Vector2D;
	public function distanceTo(IPositionable $to): float;
	
	public function moveToDirection(Vector2D $direction, float $distance): self;
	public function movedToDirection(Vector2D $direction, float $distance): IPositionable;

	public function moveToPoint(Point $point, float $distance): self;
	public function movedToPoint(Point $point, float $distance): IPositionable;

	public function toLugoPoint(): LugoPoint;
	public function toLugoVector(): LugoVector;

	public function __toString(): string;
	public function toVector2D(): Vector2D;
	public function toPoint(): Point;

	public static function fromLugoPoint(LugoPoint $lugoPoint): IPositionable;
	public static function fromLugoVector(LugoVector $lugoVector): IPositionable;
}