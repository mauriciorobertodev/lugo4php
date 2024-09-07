<?php

namespace Example\Bot;

require 'settings.php';

use Lugo4php\Direction;
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
		
		return [];
	}

	public function onDisputing(GameInspector $inspector): array
	{
		return [$inspector->makeOrderCatch()];
		try {
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
        } catch (\Exception $e) {
            error_log("Did not play this turn: " . $e->getMessage());
			return [];
        }
	}

	public function onDefending(GameInspector $inspector): array
	{
		return [$inspector->makeOrderCatch()];
	}

	public function onSupporting(GameInspector $inspector): array
	{
		return [$inspector->makeOrderCatch()];
	}
	
	public function asGoalkeeper(GameInspector $inspector, PlayerState $state): array
	{
		return [$inspector->makeOrderCatch()];
	}

	public function getMyExpectedPosition(GameInspector $reader, Mapper $mapper, int $myNumber): Point
    {
        $ballPosition = $reader->getBall()->getPosition();
        $ballRegion = $mapper->getRegionFromPoint($ballPosition);
        $fieldThird = $this->mapper->getCols() / 3;
        $ballCols = $ballRegion->getCol();

		$tacticPositions = OFFENSIVE;
        if ($ballCols < $fieldThird) {
			$tacticPositions = DEFENSIVE;
        } elseif ($ballCols < $fieldThird * 2) {
			$tacticPositions = NORMAL;
        }
      
        $position = $tacticPositions[$myNumber] ?? ['Col' => 0, 'Row' => 0];
        $expectedRegion = $mapper->getRegion($position['Col'], $position['Row']);

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
