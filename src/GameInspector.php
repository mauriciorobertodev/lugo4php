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

    public function getMyPosition(): Point
    {
        return $this->getMe()->getPosition();
    }

    public function getMyDirection(): Vector2D
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

    public function getMyScore(): float
    {
        return $this->getMyTeam()->getScore();
    }

    public function getBall(): ?Ball {
        return $this->snapshot->getBall() ? Ball::fromLugoBall($this->snapshot->getBall()) : null;
    }

    public function getPlayer(Side $side, int $number): Player {
        $team = $this->getTeam($side);

        if($team) {
            foreach ($team->getPlayers() as $playerItem) {
                if($number === $playerItem->getNumber()) {
                    return $playerItem;
                };
            }
        }

        throw new Exception(sprintf('O time do lado %s não tem o player %s', $side->toString(), $number));
    }
    
    public function getMyPlayer(int $number): Player {
        return $this->getPlayer($this->getMySide(), $number);
    }
    
    public function getOpponentPlayer(int $number): Player {
        return $this->getPlayer($this->getOpponentSide(), $number);
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

    public function getOpponentScore(): float
    {
        return $this->getOpponentTeam()->getScore();
    }

    public function getDefenseGoal(): Goal {
        return $this->getMySide() === Side::HOME ? Goal::HOME() : Goal::AWAY();
    }

    public function getAttackGoal(): Goal {
        return $this->getMySide() === Side::HOME ? Goal::AWAY() : Goal::HOME();
    }

    public function makeOrderMoveToPoint(Point $point, ?float $speed = SPECS::PLAYER_MAX_SPEED): Order {
        $direction = $this->getMe()->getPosition()->directionTo($point);
        return $this->makeOrderMoveToDirection($direction, $speed);
    }

    public function makeOrderMoveToDirection(Vector2D $direction, ?float $speed = SPECS::PLAYER_MAX_SPEED): Order
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

    public function makeOrderKickToRegion(IRegion $region, ?float $speed): Order
    {
        $direction = $this->getMe()->getPosition()->directionTo($region->getCenter());
        return $this->makeOrderKickToDirection($direction, $speed);
    }

    public function makeOrderMoveToStop(): Order
    {
        $direction = $this->getMe()->getVelocity()->getDirection();
        return $this->makeOrderMoveToDirection($direction, 0);
    }

    public function makeOrderJumpToPoint(Point $point, ?float $speed = SPECS::GOALKEEPER_JUMP_MAX_SPEED): Order
    {
        $origin = $this->getMe()->getPosition() ?? new Point();
        $direction = $origin->directionTo($point);

        $vel = Velocity::newZeroed();
        $vel->setDirection($direction);
        $vel->setSpeed($speed);

        $jump = new JumpOrder();
        $jump->setVelocity($vel->toLugoVelocity());

        return (new Order())->setJump($jump);
    }

    public function makeOrderKickToPoint(Point $point, ?float $speed = SPECS::BALL_MAX_SPEED): Order
    {
        $ballPosition = $this->getBall()?->getPosition() ?? new Point();
        $ballDirection = $ballPosition->directionTo($point);

        return $this->makeOrderKickToDirection($ballDirection, $speed);
    }

    public function makeOrderKickToDirection(Vector2D $direction, ?float $speed): Order
    {
        $vel = Velocity::newZeroed();
        $vel->setDirection($direction);
        $vel->setSpeed($speed);

        $kick = new KickOrder();
        $kick->setVelocity($vel->toLugoVelocity());

        return (new Order())->setKick($kick);
    }

    public function makeOrderKickToPlayer(Player $player, ?float $speed = SPECS::BALL_MAX_SPEED): Order {
        return $this->makeOrderKickToPoint($player->getPosition(), $speed);
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
