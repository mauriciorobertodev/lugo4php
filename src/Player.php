<?php
namespace Lugo4php;

use Lugo\Player as LugoPlayer;
use Lugo4php\Interfaces\IPlayer;
use Lugo4php\Interfaces\IRegion;
use Lugo4php\Side;
use Lugo\Team_Side;

class Player implements IPlayer {
	public function __construct(
		private int $number,
		private bool $isJumping,
		private Side $side,
		private Point $position,
		private Point $initPosition,
		private Velocity $velocity,
	) {}

	public function getNumber(): int
	{
		return $this->number;
	}

    public function getSpeed(): float
	{
		return $this->velocity->getSpeed();
	}

    public function getDirection(): Vector2D
	{
		return $this->velocity->getDirection();
	}

    public function getPosition(): Point
	{
		return $this->position;
	}

    public function getVelocity(): Velocity
	{
		return $this->velocity;
	}

    public function getTeamSide(): Side {
		return $this->side;
	}

    public function getInitPosition(): Point
	{
		return $this->initPosition;
	}

    public function getIsJumping(): bool
	{
		return $this->isJumping;
	}

	public function isGoalkeeper(): bool
	{
		return $this->getNumber() === SPECS::GOALKEEPER_NUMBER;
	}

	public function is(Player $player): bool
	{
		return $this->eq($player);
	}
	
	public function eq(Player $player): bool
	{
		return $this->side === $player->getTeamSide() && $this->number === $player->getNumber();
	}

	public function isInAttackSide(): bool
	{
		$more = $this->getPosition()->getX() > SPECS::FIELD_CENTER_X;
		return $this->side === Side::HOME ? $more : !$more;
	}
	
	public function isInDefenseSide(): bool
	{
		$less = $this->getPosition()->getX() < SPECS::FIELD_CENTER_X;
		return $this->side === Side::HOME ? $less : !$less;	
	}

	public function directionToPlayer(Player $player): Vector2D
	{
		return $this->position->directionTo($player->getPosition());
	}

	public function distanceToPlayer(Player $player): float
	{
		return $this->position->distanceTo($player->getPosition());
	}

	public function directionToRegion(IRegion $region): Vector2D
	{
		return $this->position->directionTo($region->getCenter());
	}

	public function distanceToRegion(IRegion $region): float
	{
		return $this->position->distanceTo($region->getCenter());
	}

	public function directionToPoint(Point $point): Vector2D
	{
		return $this->position->directionTo($point);
	}

	public function distanceToPoint(Point $point): float
	{
		return $this->position->distanceTo($point);
	}

	public function toLugoPlayer(): LugoPlayer
	{
		$player = new LugoPlayer();
		$player->setNumber($this->number);
		$player->setIsJumping($this->isJumping);
		$player->setPosition($this->position->toLugoPoint());
		$player->setInitPosition($this->initPosition->toLugoPoint());
		$player->setVelocity($this->velocity->toLugoVelocity());
		$player->setTeamSide($this->side === Side::HOME ? Team_Side::HOME : Team_Side::AWAY);
		return $player;
	}

	public static function fromLugoPlayer(LugoPlayer $lugoPlayer): Player {
		return new Player(
			$lugoPlayer->getNumber(),
			$lugoPlayer->getIsJumping(),
			Side::from($lugoPlayer->getTeamSide()),
			Point::fromLugoPoint($lugoPlayer->getPosition()),
			Point::fromLugoPoint($lugoPlayer->getInitPosition()),
			Velocity::fromLugoVelocity($lugoPlayer->getVelocity())
		);
	}
}