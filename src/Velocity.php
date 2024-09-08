<?php
namespace Lugo4php;

use Lugo\Velocity as LugoVelocity;
use Lugo4php\Interfaces\IVelocity;

class Velocity implements IVelocity
{
	public function __construct(private Point $direction, private float $speed) {}

	public function getDirection(): Point
	{
		return $this->direction;
	}

    public function setDirection(Point $direction): self
	{
		$this->direction = $direction;
		return $this;
	}

    public function getSpeed(): float
	{
		return $this->speed;
	}

    public function setSpeed(float $speed): self
	{
		$this->speed = $speed;
		return $this;
	}

	public function toLugoVelocity(): LugoVelocity
	{
		$velocity = new LugoVelocity();
		$velocity->setSpeed($this->speed);
		$velocity->setDirection($this->direction->toLugoVector());
		return $velocity;
	}

	public function __toString(): string
	{
		return "[{$this->direction->getX()}, {$this->direction->getY()}, {$this->speed}]";
	}

	public static function fromLugoVelocity(LugoVelocity $lugoVelocity): Velocity
	{
		return new Velocity(
			normalize(Point::fromLugoVector($lugoVelocity->getDirection())),
			$lugoVelocity->getSpeed()
		);
	}

	public static function newZeroed(): Velocity
	{
		return new Velocity(new Point(), 0);
	}
}