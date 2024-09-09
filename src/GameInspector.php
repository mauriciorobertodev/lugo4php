<?php

namespace Lugo4php;

use Exception;
use Lugo\GameSnapshot;
use Lugo\Order;
use Lugo4php\Interfaces\IGameInspector;
use Lugo4php\Interfaces\IPositionable;
use Lugo4php\Interfaces\IRegion;
use Lugo4php\Side;
use Lugo\JumpOrder;
use Lugo\KickOrder;
use Lugo\MoveOrder;
use Lugo\CatchOrder;

class GameInspector implements IGameInspector {
    private Player $me;
    private GameSnapshot $snapshot;
    private PlayerState $myState;

    public function __construct(Side $botSide, int $playerNumber, GameSnapshot $gameSnapshot) {
        $this->snapshot = $gameSnapshot;

        $me = $this->getPlayer($botSide, $playerNumber);

        if (!$me) {
            throw new \RuntimeException("Não foi possível encontrar o jogador {$botSide->toString()}-{$playerNumber}");
        }

        $this->me = $me;
        $this->myState = $this->definePlayerState();
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

    public function getMyState(): PlayerState {
        return $this->myState;
    }

    public function getMyTeam(): ?Team {
        return $this->getTeam($this->getMySide());
    }

    public function getMyNumber(): int {
        return $this->getMe()->getNumber();
    }

    public function getMySide(): Side {
        return $this->getMe()->getSide();
    }

    public function getMyPosition(): IPositionable
    {
        return $this->getMe()->getPosition();
    }

    public function getMyDirection(): IPositionable
    {
        return $this->getMe()->getDirection();
    }

    public function getMySpeed(): float
    {
        return $this->getMe()->getSpeed();
    }

    public function getMyVelocity(): Velocity
    {
        return $this->getMe()->getVelocity();
    }

    /** @return Player[] */
    public function getMyPlayers(): array {
        return $this->getMyTeam()?->getPlayers() ?? [];
    }

    public function getMyGoalkeeper(): ?Player {
        return $this->getPlayer($this->getMySide(), SPECS::GOALKEEPER_NUMBER);
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

    public function getOpponentTeam(): ?Team {
        return $this->getTeam($this->getOpponentSide());
    }

    public function getOpponentSide(): Side {
        return $this->getMySide() === Side::HOME ? Side::AWAY : Side::HOME;
    }

    /** @return Player[] */
    public function getOpponentPlayers(): array {
        return $this->getOpponentTeam()?->getPlayers() ?? [];
    }

    public function getOpponentGoalkeeper(): ?Player {
        return $this->getPlayer($this->getOpponentSide(), SPECS::GOALKEEPER_NUMBER);
    }

    public function getDefenseGoal(): Goal {
        return $this->getMySide() === Side::HOME ? Goal::HOME() : Goal::AWAY();
    }

    public function getAttackGoal(): Goal {
        return $this->getMySide() === Side::HOME ? Goal::AWAY() : Goal::HOME();
    }

    public function makeOrderMoveToTarget(IPositionable $target, ?float $speed = SPECS::PLAYER_MAX_SPEED): Order {
        $direction = (new Point(1, 1))->normalize();
        $origin = $this->getMe()->getPosition();

        if ($origin->distanceTo($target) > 0) {
            $direction = $origin->directionTo($target);
        }

        return $this->makeOrderMoveToDirection($direction, $speed);
    }

    public function makeOrderMoveToPoint(Point $point, ?float $speed = SPECS::PLAYER_MAX_SPEED): Order {
        return $this->makeOrderMoveToTarget($point, $speed);
    }

    public function makeOrderMoveToVector(Vector2D $vector, ?float $speed = SPECS::PLAYER_MAX_SPEED): Order {
        return $this->makeOrderMoveToTarget($vector, $speed);
    }

    public function makeOrderMoveToDirection(IPositionable $direction, ?float $speed = SPECS::PLAYER_MAX_SPEED): Order
    {
        $vel = Velocity::newZeroed();
        $vel->setDirection($direction);
        $vel->setSpeed($speed);

        $moveOrder = new MoveOrder();
        $moveOrder->setVelocity($vel->toLugoVelocity());

        return (new Order())->setMove($moveOrder);
    }

    public function makeOrderMoveToRegion(IRegion $region, ?float $speed = SPECS::PLAYER_MAX_SPEED): Order {
        $direction = $this->getMe()->getPosition()->directionTo($region->getCenter());
        return $this->makeOrderMoveToDirection($direction, $speed);
    }

    public function makeOrderMoveToStop(): Order
    {
        $direction = $this->getMe()->getVelocity()->getDirection();
        return $this->makeOrderMoveToDirection($direction, 0);
    }

    public function makeOrderJump(IPositionable $target, ?float $speed = SPECS::GOALKEEPER_JUMP_MAX_SPEED): Order
    {
        $origin = $this->getMe()->getPosition() ?? new Point();
        $direction = $origin->directionTo($target);

        $vel = Velocity::newZeroed();
        $vel->setDirection($direction);
        $vel->setSpeed($speed);

        $jump = new JumpOrder();
        $jump->setVelocity($vel->toLugoVelocity());

        return (new Order())->setJump($jump);
    }

    public function makeOrderKick(IPositionable $target, ?float $speed = SPECS::BALL_MAX_SPEED): Order
    {
        $ballPosition = $this->getBall()?->getPosition() ?? new Point();
        $ballDirection = $ballPosition->directionTo($target);

        $vel = Velocity::newZeroed();
        $vel->setDirection($ballDirection);
        $vel->setSpeed($speed);

        $kick = new KickOrder();
        $kick->setVelocity($vel->toLugoVelocity());

        return (new Order())->setKick($kick);
    }

    public function makeOrderKickToPlayer(Player $player, ?float $speed = SPECS::BALL_MAX_SPEED): Order {
        return $this->makeOrderKick($player->getPosition(), $speed);
    }

    public function makeOrderKickMaxSpeed(IPositionable $target): Order
    {
        return $this->makeOrderKick($target, SPECS::BALL_MAX_SPEED);
    }

    public function makeOrderCatch(): Order
    {
        return (new Order())->setCatch(new CatchOrder());
    }

    private function definePlayerState(): PlayerState
    {
        if (!$this->getBall()) {
            throw new \RuntimeException('Estado de snapshot inválido - não é possível definir o estado do jogador.');
        }

        if (!$this->getPlayer($this->getMySide(), $this->getMyNumber())) {
            throw new \RuntimeException('Não foi possível encontrar o bot no snapshot - não é possível definir o estado do jogador.');
        }

        $ballHolder = $this->getBall()->getHolder();
        if (!$ballHolder) {
            return PlayerState::DISPUTING;
        } 
        
        if($ballHolder->getSide() === $this->getMySide()) {
            if ($ballHolder->getNumber() === $this->getMyNumber()) {
                return PlayerState::HOLDING;
            }
            return PlayerState::SUPPORTING;
        }

        return PlayerState::DEFENDING;
    }
}
