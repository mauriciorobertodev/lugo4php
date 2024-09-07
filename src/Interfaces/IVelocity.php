<?php
namespace Lugo4php\Interfaces;

use Lugo4php\Point;

interface IVelocity {
	public function getDirection(): Point;
    public function setDirection(Point $direction): self;
    public function getSpeed(): float;
    public function setSpeed(float $speed): self;
}