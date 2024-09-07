<?php

namespace Example\Bot;

use Lugo4php\GameInspector;
use Lugo4php\PlayerState;
use Lugo4php\Side;
use Lugo4php\Interfaces\IBot;
use Lugo4php\Interfaces\IMapper;
use Lugo4php\Point;

class BotTester implements IBot 
{
	public function __construct(
		public int $number,
		public Side $side,
		public Point $initPosition,
		public IMapper $mapper,
	) {}

	public function onReady(GameInspector $inspector): void
	{
		// 
			var_dump(
			"onReady",
			$inspector->getTurn(),
			$inspector->getPlayer(Side::HOME, 1)->getPosition(),
			$inspector->getPlayer(Side::HOME, 2)->getPosition(),
			$inspector->getPlayer(Side::HOME, 3)->getPosition(),
			$inspector->getPlayer(Side::HOME, 4)->getPosition(),
			$inspector->getPlayer(Side::HOME, 5)->getPosition(),
			$inspector->getPlayer(Side::HOME, 6)->getPosition(),
			$inspector->getPlayer(Side::HOME, 7)->getPosition(),
			$inspector->getPlayer(Side::HOME, 8)->getPosition(),
			$inspector->getPlayer(Side::HOME, 9)->getPosition(),
			$inspector->getPlayer(Side::HOME, 10)->getPosition(),
			$inspector->getPlayer(Side::HOME, 11)->getPosition(),
		);
	}

	public function onHolding(GameInspector $inspector): array
	{
		
		return [];
	}

	public function onDisputing(GameInspector $inspector): array
	{
		return [];
	}

	public function onDefending(GameInspector $inspector): array
	{
		var_dump(
			"onDefending",
			$inspector->getTurn(),
			$inspector->getPlayer(Side::HOME, 1)->getPosition(),
			$inspector->getPlayer(Side::HOME, 2)->getPosition(),
			$inspector->getPlayer(Side::HOME, 3)->getPosition(),
			$inspector->getPlayer(Side::HOME, 4)->getPosition(),
			$inspector->getPlayer(Side::HOME, 5)->getPosition(),
			$inspector->getPlayer(Side::HOME, 6)->getPosition(),
			$inspector->getPlayer(Side::HOME, 7)->getPosition(),
			$inspector->getPlayer(Side::HOME, 8)->getPosition(),
			$inspector->getPlayer(Side::HOME, 9)->getPosition(),
			$inspector->getPlayer(Side::HOME, 10)->getPosition(),
			$inspector->getPlayer(Side::HOME, 11)->getPosition(),
		);
		return [];
	}

	public function onSupporting(GameInspector $inspector): array
	{
		return [];
	}
	
	public function asGoalkeeper(GameInspector $inspector, PlayerState $state): array
	{
		return [];
	}
}
