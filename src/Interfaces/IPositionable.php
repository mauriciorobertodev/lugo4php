<?php

namespace Lugo4php\Interfaces;

use Lugo\Point as LugoPoint;
use Lugo\Vector as LugoVector;

interface IPositionable {
    public function getX(): float;
    public function setX(float $x): IPositionable;
    public function addX(float $value): IPositionable;
    public function subtractX(float $value): IPositionable;
    public function scaleX(float $value): IPositionable;
    public function divideX(float $value): IPositionable;

    public function getY(): float;
    public function setY(float $y): IPositionable;
    public function addY(float $value): IPositionable;
    public function subtractY(float $value): IPositionable;
    public function scaleY(float $value): IPositionable;
	public function divideY(float $value): IPositionable;

	public function normalize(): IPositionable;
	public function normalized(): IPositionable;

	public function add(IPositionable | float $value): IPositionable;
	public function added(IPositionable | float $value): IPositionable;

	public function subtract(IPositionable | float $value): IPositionable;
	public function subtracted(IPositionable | float $value): IPositionable;

	public function divide(IPositionable | float $value): IPositionable;
	public function divided(IPositionable | float $value): IPositionable;

	public function scale(IPositionable | float $value): IPositionable;
	public function scaled(IPositionable | float $value): IPositionable;

	public function magnitude(): float;
	public function clone(): IPositionable;
	public function directionTo(IPositionable $to): IPositionable;
	public function distanceTo(IPositionable $to): float;
	
	public function moveToDirection(IPositionable $direction, float $distance): IPositionable;
	public function movedToDirection(IPositionable $direction, float $distance): IPositionable;

	public function moveToPoint(IPositionable $point, float $distance): IPositionable;
	public function movedToPoint(IPositionable $point, float $distance): IPositionable;

	public function toLugoPoint(): LugoPoint;
	public function toLugoVector(): LugoVector;

	public function __toString(): string;

	public static function fromLugoPoint(LugoPoint $lugoPoint): IPositionable;
	public static function fromLugoVector(LugoVector $lugoVector): IPositionable;
}