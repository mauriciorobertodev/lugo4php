<?php
namespace Lugo4php\Interfaces;

use Lugo\GameSnapshot;
use Lugo4php\Ball;
use Lugo4php\Side;
use Lugo4php\Goal;
use Lugo4php\Player;
use Lugo4php\PlayerState;
use Lugo4php\Point;
use Lugo4php\Team;
use Lugo4php\Vector2D;
use Lugo4php\Velocity;
use Lugo\Order;

interface IGameInspector {
    public function getSnapshot(): ?GameSnapshot;
    public function getTurn(): int;
    public function getBall(): ?Ball;
    public function getPlayer(Side $side, int $number): ?Player;
    public function getTeam(Side $side): ?Team;
    public function getFieldCenter():  Point;
    
    public function getAttackGoal(): Goal;
    public function getDefenseGoal(): Goal;

    public function getMe(): Player;
    public function getMyState(): PlayerState;
    public function getMyTeam(): ?Team;
    public function getMyNumber(): int;
    public function getMySide(): Side;
    public function getMyPosition(): Point;
    public function getMyDirection(): Vector2D;
    public function getMySpeed(): float;
    public function getMyVelocity(): Velocity;
    public function getMyPlayers(): array;
    public function getMyGoalkeeper(): ?Player;
    public function getMyScore(): float;
    public function getMyPlayer(int $number): ?Player;
    
    public function getOpponentPlayer(int $number): ?Player;
    public function getOpponentTeam(): ?Team;
    public function getOpponentSide(): Side;
    public function getOpponentPlayers(): array;
    public function getOpponentGoalkeeper(): ?Player;
    public function getOpponentScore(): float;

    public function makeOrderMoveToPoint(Point $point, ?float $speed): Order;
    public function makeOrderKickToPoint(Point $target, ?float $speed): Order;

    public function makeOrderMoveToDirection(Vector2D $direction, ?float $speed): Order;
    public function makeOrderKickToDirection(Vector2D $direction, ?float $speed): Order;
    
    public function makeOrderMoveToRegion(IRegion $region, ?float $speed): Order;
    public function makeOrderKickToRegion(IRegion $region, ?float $speed): Order;
    
    public function makeOrderMoveToPlayer(Player $player, ?float $speed): Order;
    public function makeOrderKickToPlayer(Player $player, ?float $speed): Order;
    
    public function makeOrderLookAtPoint(Point $point): Order;
    public function makeOrderLookAtDirection(Vector2D $direction): Order;

    public function makeOrderStop(): Order;

    public function makeOrderJumpToPoint(Point $target, ?float $speed): Order;

    public function makeOrderCatch(): Order;
}
