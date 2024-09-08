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
    public function getMe(): Player;
    public function getMyState(): PlayerState;
    public function getMyTeam(): ?Team;
    public function getMyNumber(): int;
    public function getMySide(): Side;
    public function getMyPosition(): IPositionable;
    public function getMyDirection(): IPositionable;
    public function getMySpeed(): float;
    public function getMyVelocity(): Velocity;
    public function getMyPlayers(): array;
    public function getMyGoalkeeper(): ?Player;
    public function getBall(): ?Ball;
    public function getPlayer(Side $side, int $number): ?Player;
    public function getTeam(Side $side): ?Team;
    public function getOpponentTeam(): ?Team;
    public function getOpponentSide(): Side;
    public function getOpponentPlayers(): array;
    public function getOpponentGoalkeeper(): ?Player;
    public function getDefenseGoal(): Goal;
    public function getAttackGoal(): Goal;

    public function makeOrderMoveToTarget(IPositionable $target, ?float $speed): Order;
    public function makeOrderMoveToPoint(Point $vector, ?float $speed): Order;
    public function makeOrderMoveToVector(Vector2D $vector, ?float $speed): Order;
    public function makeOrderMoveToDirection(IPositionable $direction, ?float $speed): Order;
    public function makeOrderMoveToRegion(IRegion $region, ?float $speed): Order;
    public function makeOrderMoveToStop(): Order;
    public function makeOrderJump(IPositionable $target, ?float $speed): Order;
    public function makeOrderKick(IPositionable $target, ?float $speed): Order;
    public function makeOrderKickMaxSpeed(IPositionable $target): Order;
    public function makeOrderCatch(): Order;
}
