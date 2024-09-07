<?php
namespace Lugo4php\Interfaces;

use Lugo4php\Player;
use Lugo4php\PlayerState;
use Lugo4php\Point;

interface IPositioner {
	public function getMyExpectedPositionByState(Player $player, PlayerState $state): Point;
}