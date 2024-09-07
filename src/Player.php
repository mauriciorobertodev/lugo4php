<?php
namespace Lugo4php;

use Lugo\Player as LugoPlayer;
use Lugo4php\Interfaces\IPlayer;
use Lugo4php\Side;

class Player implements IPlayer {
	public function __construct(
		private int $number,
		private bool $isJumping,
		private Side $Side,
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

    public function getDirection(): Point
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

    public function getSide(): Side {
		return $this->Side;
	}

    public function getInitPosition(): Point
	{
		return $this->initPosition;
	}

    public function getIsJumping(): bool
	{
		return $this->isJumping;
	}

	public function is(Player $player): bool
	{
		return $this->eq($player);
	}
	
	public function eq(Player $player): bool
	{
		return $this->Side === $player->getSide() && $this->number === $player->getNumber();
	}

	public function toLugoPlayer(): LugoPlayer
	{
		$player = new LugoPlayer();
		$player->setNumber($this->number);
		$player->setIsJumping($this->isJumping);
		$player->setPosition($this->position->toLugoPoint());
		$player->setInitPosition($this->position->toLugoPoint());
		$player->setVelocity($this->velocity->toLugoVelocity());
		return $player;
	}

	public static function fromLugoPlayer(LugoPlayer $lugoPlayer): Player {
		return new Player(
			$lugoPlayer->getNumber(),
			$lugoPlayer->getIsJumping(),
			Side::from($lugoPlayer->getSide()),
			Point::fromLugoPoint($lugoPlayer->getPosition()),
			Point::fromLugoPoint($lugoPlayer->getInitPosition()),
			Velocity::fromLugoVelocity($lugoPlayer->getVelocity())
		);
	}
}