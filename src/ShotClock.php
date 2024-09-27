<?php

namespace Lugo4php;

use Lugo4php\Interfaces\IShotClock;
use Lugo\ShotClock as LugoShotClock;

class ShotClock implements IShotClock
{
	public function __construct(
		private Side $side,
		private int $remainingTurns,
	){}

	public function getRemainingTurnsWithBall(): int
	{
		return $this->remainingTurns;
	}

	public function getTurnsWithBall(): int
	{
		return SPECS::SHOT_CLOCK_TIME - $this->remainingTurns;
	}

	public function getHolderSide(): Side
	{
		return $this->side;
	}

	public static function fromLugoShotClock(LugoShotClock $lugoShotClock): ShotClock
	{
		$side = Side::fromInt($lugoShotClock->getTeamSide());
		$remainingTurns = $lugoShotClock->getRemainingTurns() ?? SPECS::SHOT_CLOCK_TIME;
		return new ShotClock($side, $remainingTurns);
	}
}
