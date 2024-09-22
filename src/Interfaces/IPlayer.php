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
    public function getTeamSide(): Side;
    public function getInitPosition(): Point;
    public function getIsJumping(): bool;
    public function isGoalkeeper(): bool;
    public function is(Player $player): bool;
    public function eq(Player $player): bool;

    public function isInAttackSide(): bool;
    public function isInDefenseSide(): bool;

    public function directionToPlayer(Player $player): Vector2D;
    public function directionToPoint(Point $point): Vector2D;
    public function directionToRegion(IRegion $region): Vector2D;
    public function distanceToPlayer(Player $player): float;
    public function distanceToPoint(Point $point): float;
    public function distanceToRegion(IRegion $region): float;
}
