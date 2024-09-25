<?php
namespace Lugo4php;

use Lugo\Ball as LugoBall;
use Lugo4php\Interfaces\IBall;
use Lugo4php\Interfaces\IRegion;
use Lugo4php\Point;
use Lugo4php\Velocity;
use Lugo4php\Player;

class Ball implements IBall
{
	private Point $position;
	private Velocity $velocity;

	public function __construct(
		?Point $position,
		?Velocity $velocity,
		private ?Player $holder,
	) {
		$this->position = $position ?? new Point(SPECS::FIELD_CENTER_X, SPECS::FIELD_CENTER_Y);
		$this->velocity = $velocity ?? Velocity::newZeroed();
	}

	public function getPosition(): Point
	{
		return $this->position;
	}

    public function setPosition(Point $position): self
	{
		$this->position = $position;
		return $this;
	}

    public function getVelocity(): Velocity
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
		return $this->holder->getNumber() === $holder->getNumber() && $this->holder->getTeamSide() === $holder->getTeamSide();
	}

	public function directionToPlayer(Player $player): Vector2D
	{
		return $this->getPosition()->directionTo($player->getPosition());
	}

    public function directionToPoint(Point $point): Vector2D
	{
		return $this->getPosition()->directionTo($point);
	}

    public function directionToRegion(IRegion $region): Vector2D
	{
		return $this->getPosition()->directionTo($region->getCenter());
	}

    public function distanceToPlayer(Player $player): float
	{
		return $this->getPosition()->distanceTo($player->getPosition());
	}

    public function distanceToPoint(Point $point): float
	{
		return $this->getPosition()->distanceTo($point);
	}

    public function distanceToRegion(IRegion $region): float
	{
		return $this->getPosition()->distanceTo($region->getCenter());
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
			$lugoBall->getPosition() ? Point::fromLugoPoint($lugoBall->getPosition()) : new Point(SPECS::FIELD_CENTER_X, SPECS::FIELD_CENTER_Y),
			$lugoBall->getVelocity() ? Velocity::fromLugoVelocity($lugoBall->getVelocity()) : Velocity::newZeroed(),
			$lugoBall->getHolder() ? Player::fromLugoPlayer($lugoBall->getHolder()): null,
		);
	}

	public static function newZeroed(): Ball
	{
		return new Ball(new Point(SPECS::FIELD_CENTER_X, SPECS::FIELD_CENTER_Y), Velocity::newZeroed(), null);
	}
}