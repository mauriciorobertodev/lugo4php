<?php

namespace Lugo4php\Traits;

use Lugo4php\GameInspector;
use Lugo4php\PlayerState;

trait HasLog {
	protected GameInspector $inspector;

	public function log(string $text): void {
		if(!$this->canLog()) return;
		
		$logColors = [
			"\033[33m",  //1
			"\033[32m",  //2
			"\033[35m",  //3
			"\033[34m",  //4
			"\033[1m\033[36m",  //5
			"\033[1m\033[33m",  //6
			"\033[1m\033[32m",  //7
			"\033[1m\033[35m",  //8
			"\033[1m\033[34m",  //9
			"\033[36m",  //10
			"\033[1m\033[31m",  //11
		];

		$me = $this->inspector->getMe();

		$prefix = strtoupper($this->inspector->getMyState()->value);
		if($me->isGoalkeeper()) $prefix = 'GOALKEEPER';

		$meFormatted = $me->getNumber() < 10 ? '0' . $me->getNumber() : $me->getNumber();
		echo $logColors[$me->getNumber() - 1] . "#$meFormatted [{$prefix}] \033[0m\033[1m $text\033[0m\n";
	}

	private function canLog(): bool {
		if (getenv('BOT_LOG_ON') !== "true") {
			return false;
		}

		$isGoalKeeper = $this->inspector->getMe()->isGoalkeeper();
		$playerState = $this->inspector->getMyState();

		return match ($playerState) {
			 PlayerState::DISPUTING => getenv('LOG_ON_DISPUTING') === "true",
			 PlayerState::DEFENDING => getenv('LOG_ON_DEFENDING') === "true",
			 PlayerState::HOLDING => getenv('LOG_ON_HOLDING') === "true",
			 PlayerState::SUPPORTING => getenv('LOG_ON_SUPPORTING') === "true",
			 default => $isGoalKeeper && getenv('LOG_GOALKEEPER') === "true"
		};
	}
}