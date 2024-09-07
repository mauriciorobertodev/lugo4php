<?php
namespace Lugo4php\Interfaces;

use Lugo4php\Point;

interface IRegion {
    public function eq(IRegion $region): bool;
    public function is(IRegion $region): bool;
    public function getCol(): int;
    public function getRow(): int;
    public function getCenter(): Point;
    public function front(): IRegion;
    public function back(): IRegion;
    public function left(): IRegion;
    public function right(): IRegion;
	public function __toString(): string;
}
