<?php

namespace Lugo4php\Interfaces;

use Lugo4php\Side;

interface IShotClock 
{
	public function getRemainingTurnsWithBall(): int;
	public function getTurnsWithBall(): int;
	public function getHolderSide(): Side;
}