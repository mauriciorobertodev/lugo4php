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
use RuntimeException;

class GameInspector implements IGameInspector {
    private Player $me;
    private GameSnapshot $snapshot;
    private PlayerState $myState;

    public function __construct(Side $botSide, int $playerNumber, GameSnapshot $gameSnapshot) {
        $this->snapshot = $gameSnapshot;
        $this->me = $this->getPlayer($botSide, $playerNumber);
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

    public function hasShotClock(): bool
    {
        return (bool) $this->snapshot->getShotClock();
    }

    public function getShotClock(): ?ShotClock
    {
        return ShotClock::fromLugoShotClock($this->snapshot->getShotClock());
    }

    public function getMyState(): PlayerState {
        return $this->myState;
    }

    public function getMyTeam(): Team {
        return $this->getTeam($this->getMyTeamSide());
    }

    public function getMyNumber(): int {
        return $this->getMe()->getNumber();
    }

    public function getFieldCenter(): Point
    {
        return new Point(SPECS::FIELD_CENTER_X, SPECS::FIELD_CENTER_Y);
    }

    public function getMyTeamSide(): Side {
        return $this->getMe()->getTeamSide();
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

    public function getMyGoalkeeper(): Player {
        return $this->getPlayer($this->getMyTeamSide(), SPECS::GOALKEEPER_NUMBER);
    }
    
    public function tryGetMyGoalkeeper(): ?Player {
        return $this->tryGetPlayer($this->getMyTeamSide(), SPECS::GOALKEEPER_NUMBER);
    }

    public function getMyScore(): float
    {
        return $this->getMyTeam()->getScore();
    }

    public function getBall(): Ball {
        return  $this->snapshot->getBall() ? Ball::fromLugoBall($this->snapshot->getBall()) : Ball::newZeroed();
    }

    public function getBallHolder(): ?Player
    {
        return $this->getBall()->getHolder();
    }

    public function getBallHasHolder(): bool
    {
        return $this->getBall()->hasHolder();
    }

    public function getBallTurnsInGoalZone(): int
    {
        return $this->snapshot->getTurnsBallInGoalZone();
    }
    
    public function getBallRemainingTurnsInGoalZone(): int
    {
        return SPECS::BALL_TIME_IN_GOAL_ZONE - $this->getBallTurnsInGoalZone();   
    }

    public function getBallPosition(): Point
    {
        return $this->getBall()->getPosition();
    }

    public function getBallDirection(): Vector2D
    {
        return $this->getBall()->getDirection();
    }
   
    public function getBallSpeed(): float
    {
        return $this->getBall()->getSpeed();   
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
    
    public function tryGetPlayer(Side $side, int $number): ?Player {
        $team = $this->getTeam($side);

        if($team) {
            foreach ($team->getPlayers() as $playerItem) {
                if($number === $playerItem->getNumber()) {
                    return $playerItem;
                };
            }
        }

        return null;
    }
    
    public function getMyPlayer(int $number): Player {
        return $this->getPlayer($this->getMyTeamSide(), $number);
    }
    
    public function tryGetMyPlayer(int $number): ?Player {
        return $this->tryGetPlayer($this->getMyTeamSide(), $number);
    }
    
    public function getOpponentPlayer(int $number): Player {
        return $this->getPlayer($this->getOpponentSide(), $number);
    }
    
    public function tryGetOpponentPlayer(int $number): ?Player {
        return $this->tryGetPlayer($this->getOpponentSide(), $number);
    }

    public function getTeam(Side $side): Team {
        if($side === Side::HOME) {
            return $this->snapshot->getHomeTeam() ? Team::fromLugoTeam($this->snapshot->getHomeTeam()) : null;
        }
        return $this->snapshot->getAwayTeam() ? Team::fromLugoTeam($this->snapshot->getAwayTeam()) : null;
    }

    public function getOpponentTeam(): Team {
        return $this->getTeam($this->getOpponentSide());
    }

    public function getOpponentSide(): Side {
        return $this->getMyTeamSide() === Side::HOME ? Side::AWAY : Side::HOME;
    }

    /** @return Player[] */
    public function getOpponentPlayers(): array {
        return $this->getOpponentTeam()?->getPlayers() ?? [];
    }

    public function getOpponentGoalkeeper(): Player {
        return $this->getPlayer($this->getOpponentSide(), SPECS::GOALKEEPER_NUMBER);
    }
    
    public function tryGetOpponentGoalkeeper(): ?Player {
        return $this->tryGetPlayer($this->getOpponentSide(), SPECS::GOALKEEPER_NUMBER);
    }

    public function getOpponentScore(): float
    {
        return $this->getOpponentTeam()->getScore();
    }

    public function getDefenseGoal(): Goal {
        return $this->getMyTeamSide() === Side::HOME ? Goal::HOME() : Goal::AWAY();
    }

    public function getAttackGoal(): Goal {
        return $this->getMyTeamSide() === Side::HOME ? Goal::AWAY() : Goal::HOME();
    }

    public function makeOrderMoveToDirection(Vector2D $direction, ?float $speed = SPECS::PLAYER_MAX_SPEED): Order
    {
        if($direction->is(new Vector2D(0,0))) throw new RuntimeException(sprintf("Direção %s inválida.", $direction));

        $vel = new Velocity($direction, $speed); 

        $moveOrder = new MoveOrder();
        $moveOrder->setVelocity($vel->toLugoVelocity());

        return (new Order())->setMove($moveOrder);
    }

    public function makeOrderKickToDirection(Vector2D $direction, ?float $speed = SPECS::BALL_MAX_SPEED): Order
    {
        if($direction->is(new Vector2D(0,0))) throw new RuntimeException(sprintf("Direção %s inválida.", $direction));

        $vel = new Velocity($direction, $speed);

        $kick = new KickOrder();
        $kick->setVelocity($vel->toLugoVelocity());

        return (new Order())->setKick($kick);
    }

    public function makeOrderMoveToPoint(Point $point, ?float $speed = SPECS::PLAYER_MAX_SPEED): Order {
        $direction = $this->getMyPosition()->directionTo($point);
        return $this->makeOrderMoveToDirection($direction, $speed);
    }

    public function makeOrderKickToPoint(Point $point, ?float $speed = SPECS::BALL_MAX_SPEED): Order
    {
        $direction = $this->getBallPosition()->directionTo($point);
        return $this->makeOrderKickToDirection($direction, $speed);
    }

    public function makeOrderMoveToRegion(IRegion $region, ?float $speed = SPECS::PLAYER_MAX_SPEED): Order {
        return $this->makeOrderMoveToPoint($region->getCenter(), $speed);
    }

    public function makeOrderKickToRegion(IRegion $region, ?float $speed = SPECS::BALL_MAX_SPEED): Order
    {
        return $this->makeOrderKickToPoint($region->getCenter(), $speed);
    }

    public function makeOrderMoveToPlayer(Player $player, ?float $speed = SPECS::PLAYER_MAX_SPEED): Order {
        return $this->makeOrderMoveToPoint($player->getPosition(), $speed);
    }

    public function makeOrderKickToPlayer(Player $player, ?float $speed = SPECS::BALL_MAX_SPEED): Order {
        return $this->makeOrderKickToPoint($player->getPosition(), $speed);
    }

    public function makeOrderLookAtPoint(Point $point): Order
    {
        return $this->makeOrderLookAtDirection($this->getMyPosition()->directionTo($point));
    }
    
    public function makeOrderLookAtDirection(Vector2D $direction): Order
    {
        return $this->makeOrderMoveToDirection($direction, 0);
    }

    public function makeOrderJumpToPoint(Point $point, ?float $speed = SPECS::GOALKEEPER_JUMP_MAX_SPEED): Order
    {
        $origin = $this->getMyPosition();
        $direction = $origin->directionTo($point);
        if($direction->is(new Vector2D(0,0))) throw new RuntimeException(sprintf("Direção %s inválida.", $direction));
        $upOrDown = $direction->getY() > 0 ? new Vector2D(0, 1) : new Vector2D(0, -1);
        $vel = new Velocity($upOrDown, $speed);

        $jump = new JumpOrder();
        $jump->setVelocity($vel->toLugoVelocity());

        return (new Order())->setJump($jump);
    }

    public function tryMakeOrderMoveToDirection(Vector2D $direction, ?float $speed = SPECS::PLAYER_MAX_SPEED): ?Order
    {
        if($direction->is(new Vector2D(0,0))) return null;

        $vel = new Velocity($direction, $speed); 

        $moveOrder = new MoveOrder();
        $moveOrder->setVelocity($vel->toLugoVelocity());

        return (new Order())->setMove($moveOrder);
    }

    public function tryMakeOrderKickToDirection(Vector2D $direction, ?float $speed = SPECS::BALL_MAX_SPEED): ?Order
    {
        if($direction->is(new Vector2D(0,0))) return null;

        $vel = new Velocity($direction, $speed);

        $kick = new KickOrder();
        $kick->setVelocity($vel->toLugoVelocity());

        return (new Order())->setKick($kick);
    }

    public function tryMakeOrderMoveToPoint(Point $point, ?float $speed = SPECS::PLAYER_MAX_SPEED): ?Order {
        $direction = $this->getMe()->getPosition()->directionTo($point);
        return $this->tryMakeOrderMoveToDirection($direction, $speed);
    }

    public function tryMakeOrderKickToPoint(Point $point, ?float $speed = SPECS::BALL_MAX_SPEED): ?Order
    {
        $ballPosition = $this->getBall()?->getPosition() ?? new Point();
        $ballDirection = $ballPosition->directionTo($point);

        return $this->tryMakeOrderKickToDirection($ballDirection, $speed);
    }

    public function tryMakeOrderMoveToRegion(IRegion $region, ?float $speed = SPECS::PLAYER_MAX_SPEED): ?Order {
        return $this->tryMakeOrderMoveToPoint($region->getCenter(), $speed);
    }

    public function tryMakeOrderKickToRegion(IRegion $region, ?float $speed = SPECS::BALL_MAX_SPEED): ?Order
    {
        return $this->tryMakeOrderKickToPoint($region->getCenter(), $speed);
    }

    public function tryMakeOrderMoveToPlayer(Player $player, ?float $speed = SPECS::PLAYER_MAX_SPEED): ?Order {
        return $this->tryMakeOrderMoveToPoint($player->getPosition(), $speed);
    }

    public function tryMakeOrderKickToPlayer(Player $player, ?float $speed = SPECS::BALL_MAX_SPEED): ?Order {
        return $this->tryMakeOrderKickToPoint($player->getPosition(), $speed);
    }

    public function tryMakeOrderLookAtPoint(Point $point): ?Order
    {
        return $this->tryMakeOrderLookAtDirection($this->getMyPosition()->directionTo($point));
    }
    
    public function tryMakeOrderLookAtDirection(Vector2D $direction): ?Order
    {
        return $this->tryMakeOrderMoveToDirection($direction, 0);
    }

    public function tryMakeOrderJumpToPoint(Point $point, ?float $speed = SPECS::GOALKEEPER_JUMP_MAX_SPEED): ?Order
    {
        $origin = $this->getMyPosition();
        $direction = $origin->directionTo($point);
        if($direction->is(new Vector2D(0,0))) return null;
        $upOrDown = $direction->getY() > 0 ? new Vector2D(0, 1) : new Vector2D(0, -1);
        $vel = new Velocity($upOrDown, $speed);

        $jump = new JumpOrder();
        $jump->setVelocity($vel->toLugoVelocity());

        return (new Order())->setJump($jump);
    }

    public function makeOrderStop(): Order
    {
        return $this->makeOrderLookAtDirection($this->getMyDirection());
    }

    public function makeOrderCatch(): Order
    {
        return (new Order())->setCatch(new CatchOrder());
    }

    private function definePlayerState(): PlayerState
    {
        $ballHolder = $this->getBall()->getHolder();
        if (!$ballHolder) {
            return PlayerState::DISPUTING;
        } 

        if($ballHolder->is($this->getMe())) {
            return PlayerState::HOLDING;
        }
        
        if($ballHolder->getTeamSide() === $this->getMyTeamSide()) {
            return PlayerState::SUPPORTING;
        }

        return PlayerState::DEFENDING;
    }
}
