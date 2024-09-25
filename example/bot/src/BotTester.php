<?php

namespace Example\Bot;

use Lugo4php\Formation;
use Lugo4php\GameInspector;
use Lugo4php\Interfaces\IBot;
use Lugo4php\PlayerState;
use Lugo4php\Interfaces\IMapper;
use Lugo4php\Mapper;
use Lugo4php\Point;

class BotTester implements IBot
{
	public function __construct(public IMapper $mapper) {}

	public function onDisputing(GameInspector $inspector): array
	{
		$orders = [];
		$ballPosition = $inspector->getBall()->getPosition();

		$ballRegion = $this->mapper->getRegionFromPoint($ballPosition);
		$myRegion = $this->mapper->getRegionFromPoint($inspector->getMyPosition());

		$moveDestination = $this->getMyExpectedPosition($inspector, $this->mapper, $inspector->getMyNumber());

		if ($myRegion->distanceToRegion($ballRegion) <= 2) {
			$moveDestination = $ballPosition;
		}

		$moveOrder = $inspector->makeOrderMoveToPoint($moveDestination);
		$catchOrder = $inspector->makeOrderCatch();

		$orders[] = $moveOrder;
		$orders[] = $catchOrder;

		return $orders;
	}

	public function onHolding(GameInspector $inspector): array
	{
		$orders = [];

		$attackGoalCenter = $inspector->getAttackGoal()->getCenter();
		$opponentGoalRegion = $this->mapper->getRegionFromPoint($attackGoalCenter);
		$currentRegion = $this->mapper->getRegionFromPoint($inspector->getMyPosition());

		if ($currentRegion->distanceToRegion($opponentGoalRegion) <= 2) {
			$orders[] = $inspector->makeOrderKickToPoint($attackGoalCenter);
		} else {
			$orders[] = $inspector->makeOrderMoveToPoint($attackGoalCenter);
		}

		return $orders;
	}

	public function onDefending(GameInspector $inspector): array
	{
		$orders = [];
		$ballPosition = $inspector->getBall()->getPosition();
		$ballRegion = $this->mapper->getRegionFromPoint($ballPosition);
		$myRegion = $this->mapper->getRegionFromPoint($inspector->getMyPosition());

		// Por padrão, vou ficar na minha posição tática
		$moveDestination = $this->getMyExpectedPosition($inspector, $this->mapper, $inspector->getMyNumber());

		// Se a bola estiver no máximo 2 blocos de distância de mim, vou em direção à bola
		if ($myRegion->distanceToRegion($ballRegion) <= 2) {
			$moveDestination = $ballPosition;
		}

		$moveOrder = $inspector->makeOrderMoveToPoint($moveDestination);
		$catchOrder = $inspector->makeOrderCatch();

		$orders[] = $moveOrder;
		$orders[] = $catchOrder;

		return $orders;
	}

	public function onSupporting(GameInspector $inspector): array
	{
		$orders = [];
		$ballPosition = $inspector->getBall()->getHolder()->getPosition();
		$orders[] = $inspector->makeOrderMoveToPoint($ballPosition);

		return $orders;
	}
	
	public function asGoalkeeper(GameInspector $inspector, PlayerState $state): array
	{		
		$orders = [];
		$position = $inspector->getBall()->getPosition();

		if ($state !== PlayerState::DISPUTING) {
			$position = $inspector->getDefenseGoal()->getCenter();
		}

		$orders[] = $inspector->makeOrderMoveToPoint($position);
		$orders[] = $inspector->makeOrderCatch();
		
		return $orders;
	}

	public function getMyExpectedPosition(GameInspector $inspector, Mapper $mapper): Point
    {
        $ballPosition = $inspector->getBall()->getPosition();
        $ballRegion = $mapper->getRegionFromPoint($ballPosition);
        $fieldThird = $this->mapper->getCols() / 3;
        $ballCols = $ballRegion->getCol();

		$tacticPositions = Formation::createFromArray(OFFENSIVE);
        if ($ballCols < $fieldThird) {
			$tacticPositions = Formation::createFromArray(DEFENSIVE);
        } elseif ($ballCols < $fieldThird * 2) {
			$tacticPositions = Formation::createFromArray(NORMAL);
        }

		$position = $tacticPositions->getPositionOf($inspector->getMyNumber());
    
        $expectedRegion = $mapper->getRegion($position->getX(), $position->getY());

        return $expectedRegion->getCenter();
    }

	public function onReady(GameInspector $inspector): void {}
	
	public function beforeActions(GameInspector $inspector): void {}

	public function afterActions(GameInspector $inspector): void {}
}
