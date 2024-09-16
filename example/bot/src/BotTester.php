<?php

namespace Example\Bot;

use Lugo4php\DefaultBotBase;
use Lugo4php\Formation;
use Lugo4php\GameInspector;
use Lugo4php\PlayerState;
use Lugo4php\Side;
use Lugo4php\Interfaces\IMapper;
use Lugo4php\Mapper;
use Lugo4php\Point;

class BotTester extends DefaultBotBase
{
	public function __construct(
		public int $number,
		public Side $side,
		public Point $initPosition,
		public IMapper $mapper,
	) {}

	public function onDisputing(GameInspector $inspector): array
	{
		$this->log('onDisputing');
		$orders = [];
		$me = $inspector->getMe();
		$ballPosition = $inspector->getBall()->getPosition();

		$ballRegion = $this->mapper->getRegionFromPoint($ballPosition);
		$myRegion = $this->mapper->getRegionFromPoint($me->getPosition());

		$moveDestination = $this->getMyExpectedPosition($inspector, $this->mapper, $this->number);

		if ($myRegion->distanceToRegion($ballRegion) <= 2) {
			$moveDestination = $ballPosition;
		}

		$moveOrder = $inspector->makeOrderMoveToTarget($moveDestination);

		$catchOrder = $inspector->makeOrderCatch();

		$orders[] = $moveOrder;
		$orders[] = $catchOrder;

		return $orders;
	}

	public function onHolding(GameInspector $inspector): array
	{
		$this->log('onHolding');
		$orders = [];
		$me = $inspector->getMe();

		$attackGoalCenter = $inspector->getAttackGoal()->getCenter();
		$opponentGoalRegion = $this->mapper->getRegionFromPoint($attackGoalCenter);
		$currentRegion = $this->mapper->getRegionFromPoint($me->getPosition());

		if ($currentRegion->distanceToRegion($opponentGoalRegion) <= 2) {
			$orders[] = $inspector->makeOrderKick($attackGoalCenter);
		} else {
			$orders[] = $inspector->makeOrderMoveToTarget($attackGoalCenter);
		}

		return $orders;
	}

	public function onDefending(GameInspector $inspector): array
	{
		$this->log('onDefending');
		$orders = [];
		$me = $inspector->getMe();
		$ballPosition = $inspector->getBall()->getPosition();
		$ballRegion = $this->mapper->getRegionFromPoint($ballPosition);
		$myRegion = $this->mapper->getRegionFromPoint($me->getPosition());

		// Por padrão, vou ficar na minha posição tática
		$moveDestination = $this->getMyExpectedPosition($inspector, $this->mapper, $this->number);

		// Se a bola estiver no máximo 2 blocos de distância de mim, vou em direção à bola
		if ($myRegion->distanceToRegion($ballRegion) <= 2) {
			$moveDestination = $ballPosition;
		}

		$moveOrder = $inspector->makeOrderMoveToTarget($moveDestination);
		$catchOrder = $inspector->makeOrderCatch();

		$orders[] = $moveOrder;
		$orders[] = $catchOrder;

		return $orders;
	}

	public function onSupporting(GameInspector $inspector): array
	{
		$this->log('onSupporting');
		$orders = [];
		$ballPosition = $inspector->getBall()->getHolder()->getPosition();
		$orders[] = $inspector->makeOrderMoveToTarget($ballPosition);

		return $orders;
	}
	
	public function asGoalkeeper(GameInspector $inspector, PlayerState $state): array
	{
		$this->log('asGoalkeeper');
		
		$orders = [];
		$position = $inspector->getBall()->getPosition();

		if ($state !== PlayerState::DISPUTING) {
			$position = $inspector->getDefenseGoal()->getCenter();
		}

		$orders[] = $inspector->makeOrderMoveToTarget($position);
		$orders[] = $inspector->makeOrderCatch();
		
		return $orders;
	}

	public function getMyExpectedPosition(GameInspector $reader, Mapper $mapper): Point
    {
        $ballPosition = $reader->getBall()->getPosition();
        $ballRegion = $mapper->getRegionFromPoint($ballPosition);
        $fieldThird = $this->mapper->getCols() / 3;
        $ballCols = $ballRegion->getCol();

		$tacticPositions = Formation::createFromArray(OFFENSIVE);
        if ($ballCols < $fieldThird) {
			$tacticPositions = Formation::createFromArray(DEFENSIVE);
        } elseif ($ballCols < $fieldThird * 2) {
			$tacticPositions = Formation::createFromArray(NORMAL);
        }

		$position = $tacticPositions->getPositionOf($this->number);
    
        $expectedRegion = $mapper->getRegion($position->getX(), $position->getY());

        return $expectedRegion->getCenter();
    }
}
