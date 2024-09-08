<?php

namespace Example\Bot;

use Lugo4php\Direction;
use Lugo4php\Formation;
use Lugo4php\GameInspector;
use Lugo4php\PlayerState;
use Lugo4php\Side;
use Lugo4php\Interfaces\IBot;
use Lugo4php\Interfaces\IMapper;
use Lugo4php\Mapper;
use Lugo4php\Point;
use Lugo4php\Region;

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
	}

	public function onHolding(GameInspector $inspector): array
	{

			$orders = [];
			$me = $inspector->getMe();

			$attackGoalCenter = $inspector->getAttackGoal()->getCenter();
			$opponentGoal = $this->mapper->getRegionFromPoint($attackGoalCenter);
			$currentRegion = $this->mapper->getRegionFromPoint($me->getPosition());

			$myOrder = null;

			if ($this->isINear($currentRegion, $opponentGoal)) {
				$myOrder = $inspector->makeOrderKickMaxSpeed($attackGoalCenter);
			} else {
				$myOrder = $inspector->makeOrderMoveMaxSpeed($attackGoalCenter);
			}

			$orders[] = $myOrder;
			return $orders;
		

	}

	public function onDisputing(GameInspector $inspector): array
	{

            $orders = [];
            $me = $inspector->getMe();
            $ballPosition = $inspector->getBall()->getPosition();

            $ballRegion = $this->mapper->getRegionFromPoint($ballPosition);
            $myRegion = $this->mapper->getRegionFromPoint($me->getPosition());

            // By default, I will stay at my tactic position
            $moveDestination = $this->getMyExpectedPosition($inspector, $this->mapper, $this->number);

            // If the ball is max 2 blocks away from me, I will move toward the ball
            if ($this->isINear($myRegion, $ballRegion)) {
                $moveDestination = $ballPosition;
            }

            $moveOrder = $inspector->makeOrderMoveMaxSpeed($moveDestination);

            // Try other ways to create a move Order
            $moveOrder = $inspector->makeOrderMoveByDirection(Direction::BACKWARD);

            // We can ALWAYS try to catch the ball if we are not holding it
            $catchOrder = $inspector->makeOrderCatch();

            $orders[] = $moveOrder;
            $orders[] = $catchOrder;

            return $orders;

	}

	public function onDefending(GameInspector $inspector): array
	{

			$orders = [];
			$me = $inspector->getMe();
			$ballPosition = $inspector->getBall()->getPosition();
			$ballRegion = $this->mapper->getRegionFromPoint($ballPosition);
			$myRegion = $this->mapper->getRegionFromPoint($me->getPosition());

			// Por padrão, vou ficar na minha posição tática
			$moveDestination = $this->getMyExpectedPosition($inspector, $this->mapper, $this->number);

			// Se a bola estiver no máximo 2 blocos de distância de mim, vou em direção à bola
			if ($this->isINear($myRegion, $ballRegion)) {
				$moveDestination = $ballPosition;
			}

			$moveOrder = $inspector->makeOrderMoveMaxSpeed($moveDestination);
			$catchOrder = $inspector->makeOrderCatch();

			$orders[] = $moveOrder;
			$orders[] = $catchOrder;

			return $orders;

	}

	public function onSupporting(GameInspector $inspector): array
	{

			$orders = [];
			$me = $inspector->getMe();
			$ballHolderPosition = $inspector->getBall()->getPosition();
			$myOrder = $inspector->makeOrderMoveMaxSpeed($ballHolderPosition);

			$orders[] = $myOrder;
			return $orders;

	}
	
	public function asGoalkeeper(GameInspector $inspector, PlayerState $state): array
	{
			$orders = [];
			$me = $inspector->getMe();
			$position = $inspector->getBall()->getPosition();

			if ($state !== PlayerState::DISPUTING) {
				$position = $inspector->getDefenseGoal()->getCenter();
			}

			$myOrder = $inspector->makeOrderMoveMaxSpeed($position);

			$orders[] = $myOrder;
			$orders[] = $inspector->makeOrderCatch();
			
			return $orders;
	}

	public function getMyExpectedPosition(GameInspector $reader, Mapper $mapper, int $myNumber): Point
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

	private function isINear(Region $myPosition, Region $targetPosition): bool
	{
		$minDist = 2;
		$colDist = $myPosition->getCol() - $targetPosition->getCol();
		$rowDist = $myPosition->getRow() - $targetPosition->getRow();
		return sqrt($colDist * $colDist + $rowDist * $rowDist) <= $minDist;
	}
}
