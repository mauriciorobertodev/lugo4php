<?php
namespace Lugo4php\Interfaces;

use Lugo4php\Player;
use Lugo4php\Point;
use Lugo4php\Vector2D;
use Lugo4php\Velocity;

interface IBall
{
	public function getPosition(): ?Point;
    public function setPosition(Point $var): self;
    public function getVelocity(): ?Velocity;
    public function setVelocity(Velocity $var): self;
    public function getDirection(): Vector2D;
    public function getSpeed(): float;
    public function hasHolder(): bool;
    public function getHolder(): ?Player;
    public function holderIs(Player $holder): bool;
}