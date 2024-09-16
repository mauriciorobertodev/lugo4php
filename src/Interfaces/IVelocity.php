<?php

namespace Lugo4php\Interfaces;

use Lugo4php\Vector2D;

interface IVelocity {
	public function getDirection(): Vector2D;
    public function setDirection(Vector2D $direction): self;
    public function getSpeed(): float;
    public function setSpeed(float $speed): self;
}