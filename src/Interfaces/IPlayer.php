<?php

namespace Lugo4php\Interfaces;

use Lugo4php\Side;
use Lugo4php\Player;
use Lugo4php\Point;
use Lugo4php\Velocity;

interface IPlayer
{
    public function getNumber(): int;
    public function getSpeed(): float;
    public function getDirection(): Point;
    public function getPosition(): Point;
    public function getVelocity(): Velocity;
    public function getSide(): Side;
    public function getInitPosition(): Point;
    public function getIsJumping(): bool;
    public function is(Player $player): bool;
    public function eq(Player $player): bool;
}
