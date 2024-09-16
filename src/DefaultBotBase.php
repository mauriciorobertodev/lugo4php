<?php
namespace Lugo4php;

use Lugo4php\Interfaces\IBot;
use Lugo4php\Traits\HasLog;

abstract class DefaultBotBase implements IBot
{
	use HasLog;

	abstract public function onDisputing(GameInspector $inspector): array;
	abstract public function onHolding(GameInspector $inspector): array;
	abstract public function onDefending(GameInspector $inspector): array;
	abstract public function onSupporting(GameInspector $inspector): array;
	abstract public function asGoalkeeper(GameInspector $inspector, PlayerState $state): array;

	public function onReady(GameInspector $inspector): void {
		// 
	}

	public function beforeAction(GameInspector $inspector): void {
		$this->inspector = $inspector;
	}
}

