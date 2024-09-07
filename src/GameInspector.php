<?php

namespace Lugo4php;

use Exception;
use Lugo\GameSnapshot;
use Lugo\Order;
use Lugo4php\Interfaces\IGameInspector;
use Lugo4php\Side;
use Lugo\Jump;
use Lugo\Kick;
use Lugo\Move;
use Lugo\CatchOrder;

class GameInspector implements IGameInspector {
    private int $myNumber;
    private Player $me;
    private Side $mySide;
    private GameSnapshot $snapshot;

    public function __construct(Side $botSide, int $playerNumber, GameSnapshot $gameSnapshot) {
        $this->mySide = $botSide;
        $this->myNumber = $playerNumber;
        $this->snapshot = $gameSnapshot;

        $me = $this->getPlayer($this->mySide, $this->myNumber);

        if (!$me) {
            throw new \RuntimeException("Não foi possível encontrar o jogador {$botSide}-{$playerNumber}");
        }

        $this->me = $me;
    }

    public function getSnapshot(): GameSnapshot {
        return $this->snapshot;
    }

    public function getTurn(): int {
        return $this->snapshot->getTurn();
    }

    public function getMe(): Player {
        return $this->me;
    }

    public function getBall(): ?Ball {
        return $this->snapshot->getBall() ? Ball::fromLugoBall($this->snapshot->getBall()) : null;
    }

    public function getPlayer(Side $side, int $number): ?Player {
        $team = $this->getTeam($side);

        if($team) {
            foreach ($team->getPlayers() as $playerItem) {
                if($number === $playerItem->getNumber()) {
                    return $playerItem;
                };
            }
        }

        throw new Exception('Não tem o team ou o player');

        return null;
    }

    public function getTeam(Side $side): ?Team {
        if($side === Side::HOME) {
            return $this->snapshot->getHomeTeam() ? Team::fromLugoTeam($this->snapshot->getHomeTeam()) : null;
        }
        return $this->snapshot->getAwayTeam() ? Team::fromLugoTeam($this->snapshot->getAwayTeam()) : null;
    }

    public function getMyTeam(): ?Team {
        return $this->getTeam($this->mySide);
    }

    public function getOpponentTeam(): ?Team {
        return $this->getTeam($this->getOpponentSide());
    }

    public function getMySide(): Side {
        return $this->mySide;
    }

    public function getOpponentSide(): Side {
        return $this->mySide === Side::HOME ? Side::AWAY : Side::HOME;
    }

    /** @return Player[] */
    public function getMyPlayers(): array {
        return $this->getMyTeam()?->getPlayers() ?? [];
    }

    /** @return Player[] */
    public function getOpponentPlayers(): array {
        return $this->getOpponentTeam()?->getPlayers() ?? [];
    }

    public function getMyTeamGoalkeeper(): ?Player {
        return $this->getPlayer($this->getMySide(), SPECS::GOALKEEPER_NUMBER);
    }

    public function getOpponentGoalkeeper(): ?Player {
        return $this->getPlayer($this->getOpponentSide(), SPECS::GOALKEEPER_NUMBER);
    }

    public function getDefenseGoal(): Goal {
        return $this->mySide === Side::HOME ? Goal::HOME() : Goal::AWAY();
    }

    public function getAttackGoal(): Goal {
        return $this->mySide === Side::AWAY ? Goal::AWAY() : Goal::HOME();
    }

    public function makeOrderMove(Point $target, float $speed): Order
    {
        return $this->makeOrderMoveFromPoint($this->me->getPosition() ?? new Point(), $target, $speed);
    }

    public function makeOrderMoveMaxSpeed(Point $target): Order
    {
        return $this->makeOrderMoveFromPoint($this->me->getPosition() ?? new Point(), $target, SPECS::PLAYER_MAX_SPEED);
    }

    public function makeOrderMoveFromPoint(Point $origin, Point $target, float $speed): Order {
        if (abs(getDistanceBetween($origin, $target)) > 0) {
            $direction = getDirectionTo($origin, $target);
        }

        $vel = Velocity::newZeroed();
        $vel->setDirection($direction);
        $vel->setSpeed($speed);

        $moveOrder = new Move();
        $moveOrder->setVelocity($vel->toLugoVelocity());

        return (new Order())->setMove($moveOrder);
    }

    public function makeOrderMoveFromVector(Point $direction, float $speed): Order
    {
        $origin = $this->me->getPosition() ?? new Point();
        $targetPoint = targetFrom($direction, $origin);
        return $this->makeOrderMoveFromPoint($origin, $targetPoint, $speed);
    }

    public function makeOrderMoveByDirection(Direction $direction, ?float $speed = null): Order
    {
        $directionTarget = $this->getOrientationByDirection($direction);
        return $this->makeOrderMoveFromVector($directionTarget, $speed ?? SPECS::PLAYER_MAX_SPEED);
    }

    public function makeOrderMoveToStop(): Order
    {
        $myDirection = $this->getMe()->getVelocity()->getDirection() ?? $this->getOrientationByDirection(Direction::FORWARD);
        return $this->makeOrderMoveFromVector($myDirection, 0);
    }

    public function makeOrderJump(Point $target, float $speed): Order
    {
        $origin = $this->me->getPosition() ?? new Point();
        $direction = getDirectionTo($origin, $target);
        $vel = Velocity::newZeroed();
        $vel->setDirection($direction);
        $vel->setSpeed($speed);

        $jump = new Jump();
        $jump->setVelocity($vel);

        return (new Order())->setJump($jump);
    }

    public function makeOrderKick(Point $target, float $speed): Order
    {
        $ballPosition = $this->getBall()?->getPosition() ?? new Point();
        $ballVelocity = $this->getBall()?->getVelocity()?->getDirection() ?? new Point();
        $ballExpectedDirection = getDirectionTo($ballPosition, $target);

        $vel = Velocity::newZeroed();
        $vel->setDirection($ballExpectedDirection);
        $vel->setSpeed($speed);

        $kick = new Kick();
        $kick->setVelocity($vel);

        return (new Order())->setKick($kick);
    }

    public function makeOrderKickMaxSpeed(Point $target): Order
    {
        return $this->makeOrderKick($target, SPECS::BALL_MAX_SPEED);
    }

    public function makeOrderCatch(): Order
    {
        return (new Order())->setCatch(new CatchOrder());
    }

    public function getOrientationByDirection(Direction $direction): Point
    {
        switch ($direction) {
            case Direction::FORWARD:
                $directionTarget = Orientation::EAST();
                if ($this->mySide === Side::AWAY) {
                    $directionTarget = Orientation::WEST();
                }
                break;

            case Direction::BACKWARD:
                $directionTarget = Orientation::WEST();
                if ($this->mySide === Side::AWAY) {
                    $directionTarget = Orientation::EAST();
                }
                break;

            case Direction::LEFT:
                $directionTarget = Orientation::NORTH();
                if ($this->mySide === Side::AWAY) {
                    $directionTarget = Orientation::SOUTH();
                }
                break;

            case Direction::RIGHT:
                $directionTarget = Orientation::SOUTH();
                if ($this->mySide === Side::AWAY) {
                    $directionTarget = Orientation::NORTH();
                }
                break;

            case Direction::BACKWARD_LEFT:
                $directionTarget = Orientation::NORTH_WEST();
                if ($this->mySide === Side::AWAY) {
                    $directionTarget = Orientation::SOUTH_EAST();
                }
                break;

            case Direction::BACKWARD_RIGHT:
                $directionTarget = Orientation::SOUTH_WEST();
                if ($this->mySide === Side::AWAY) {
                    $directionTarget = Orientation::NORTH_EAST();
                }
                break;

            case Direction::FORWARD_LEFT:
                $directionTarget = Orientation::NORTH_EAST();
                if ($this->mySide === Side::AWAY) {
                    $directionTarget = Orientation::SOUTH_WEST();
                }
                break;

            case Direction::FORWARD_RIGHT:
                $directionTarget = Orientation::SOUTH_EAST();
                if ($this->mySide === Side::AWAY) {
                    $directionTarget = Orientation::NORTH_WEST();
                }
                break;

            default:
                throw new \Exception("Unknown direction {$direction}");
        }

        return $directionTarget;
    }
}
