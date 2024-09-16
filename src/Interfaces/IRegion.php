<?php
namespace Lugo4php\Interfaces;

use Lugo4php\Player;
use Lugo4php\Point;

interface IRegion {
    public function eq(IRegion $region): bool;
    public function is(IRegion $region): bool;
    public function getCol(): int;
    public function getRow(): int;
    public function getCenter(): Point;
    public function frontRight(): IRegion;
    public function front(): IRegion;
    public function frontLeft(): IRegion;
    public function backRight(): IRegion;
    public function back(): IRegion;
    public function backLeft(): IRegion;
    public function left(): IRegion;
    public function right(): IRegion;
    public function coordinates(): IPositionable;
    public function distanceToRegion(IRegion $region): float;
    public function distanceToPoint(Point $point): float;
    public function containsPlayer(Player $player): bool;
	public function __toString(): string;
}
