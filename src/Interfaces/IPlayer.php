<?php

namespace Lugo4php\Interfaces;

use Lugo4php\Side;
use Lugo4php\Player;
use Lugo4php\Point;
use Lugo4php\Vector2D;
use Lugo4php\Velocity;

interface IPlayer
{
    public function getNumber(): int;
    public function getSpeed(): float;
    public function getDirection(): Vector2D;
    public function getPosition(): Point;
    public function getVelocity(): Velocity;
    public function getSide(): Side;
    public function getInitPosition(): Point;
    public function getIsJumping(): bool;
    public function isGoalkeeper(): bool;
    public function is(Player $player): bool;
    public function eq(Player $player): bool;

    // public function isInAttackSide(): bool;
    // public function isInDefenseSide(): bool;
    // public function getDirectionToPlayer(Player $player): Vector2D;
    // public function getDirectionToPoint(Point $point): Vector2D;
    // public function getDirectionToRegion(IRegion $region): Vector2D;
    // public function getDistanceToPlayer(Player $player): float;
    // public function getDistanceToPoint(Point $point): float;
    // public function getDistanceToRegion(IRegion $region): float;
}
