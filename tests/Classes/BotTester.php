<?php

namespace Tests\Classes;

use Lugo4php\GameInspector;
use Lugo4php\Interfaces\IBot;
use Lugo4php\PlayerState;
use Lugo4php\Point;

class BotTester implements IBot
{
	public Point $targetPoint;
	
	public function __construct()
	{
		// $this->targetPoint = new Point(5000, 5000);
		$this->targetPoint = randomPoint();
	}

	public function onDisputing(GameInspector $inspector): array
	{
		$orders = [
			'onDisputing',
			$inspector->makeOrderMoveToPoint($this->targetPoint),
			$inspector->makeOrderCatch(),
			$inspector->makeOrderMoveToPoint($this->targetPoint),
			$inspector->makeOrderCatch(),
			$inspector->makeOrderMoveToPoint($this->targetPoint),
		];

		return $orders;
	}

	public function onHolding(GameInspector $inspector): array
	{
		$orders = [
			'onHolding',
			$inspector->makeOrderKickToPoint($this->targetPoint)
		];

		return $orders;
	}

	public function onDefending(GameInspector $inspector): array
	{
		$orders = [
			'onDefending',
			$inspector->makeOrderMoveToPoint($this->targetPoint),
			$inspector->makeOrderCatch(),
			$inspector->makeOrderMoveToPoint($this->targetPoint),
		];

		return $orders;
	}

	public function onSupporting(GameInspector $inspector): array
	{
		$orders = [
			'onSupporting',
			$inspector->makeOrderMoveToPoint($this->targetPoint),
			$inspector->makeOrderCatch(),
			$inspector->makeOrderMoveToPoint($this->targetPoint),
			$inspector->makeOrderCatch(),
		];

		return $orders;
	}
	
	public function asGoalkeeper(GameInspector $inspector, PlayerState $state): array
	{		
		$orders = [
			'asGoalkeeper',
			$inspector->makeOrderCatch()
		];
		
		return $orders;
	}

	public function onReady(GameInspector $inspector): void {}

	public function beforeActions(GameInspector $inspector): void {}
	public function afterActions(GameInspector $inspector): void {}
}