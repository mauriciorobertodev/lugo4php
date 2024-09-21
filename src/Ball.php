<?php
namespace Lugo4php;

use Lugo\Ball as LugoBall;
use Lugo4php\Interfaces\IBall;
use Lugo4php\Point;
use Lugo4php\Velocity;
use Lugo4php\Player;

class Ball implements IBall
{
	public function __construct(
		private ?Point $position,
		private ?Velocity $velocity,
		private ?Player $holder,
	) {}

	public function getPosition(): ?Point
	{
		return $this->position;
	}

    public function setPosition(Point $position): self
	{
		$this->position = $position;
		return $this;
	}

    public function getVelocity(): ?Velocity
	{
		return $this->velocity;
	}
	
    public function setVelocity(Velocity $velocity): self
	{
		$this->velocity = $velocity;
		return $this;
	}

	public function getDirection(): Vector2D
	{
		return $this->getVelocity()->getDirection();
	}

	public function getSpeed(): float
	{
		return $this->getVelocity()->getSpeed();
	}

    public function hasHolder(): bool
	{
		return (bool) $this->holder;
	}

    public function getHolder(): ?Player
	{
		return $this->holder;
	}

	public function holderIs(Player $holder): bool
	{
		if(!$this->holder) return false;
		return $this->holder->getNumber() === $holder->getNumber() && $this->holder->getSide() === $holder->getSide();
	}

	public function toLugoBall(): LugoBall
	{
		$ball = new LugoBall();
		$ball->setHolder($this->getHolder()?->toLugoPlayer());
		$ball->getVelocity($this->getVelocity()?->toLugoVelocity());
		$ball->getPosition($this->getPosition()?->toLugoPoint());
		return $ball;
	}

	public static function fromLugoBall(LugoBall $lugoBall): Ball
	{
		return new Ball(
			$lugoBall->getPosition() ? Point::fromLugoPoint($lugoBall->getPosition()) : null,
			$lugoBall->getVelocity() ? Velocity::fromLugoVelocity($lugoBall->getVelocity()) : null,
			$lugoBall->getHolder() ? Player::fromLugoPlayer($lugoBall->getHolder()): null,
		);
	}
}