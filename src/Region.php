<?php

namespace Lugo4php;

use Lugo4php\Interfaces\IRegion;
use Lugo4php\Side;
use Lugo4php\Mapper;
use Lugo4php\Point;

class Region implements IRegion {
    public function __construct(
        protected int $col, 
        protected int $row, 
        protected Side $side, 
        protected Point $center, 
        protected Mapper $mapper
    ) {}

    public function is(IRegion $region): bool {
        return $this->eq($region);
    }

    public function eq(IRegion $region): bool {
        return $region->getCol() === $this->col && $region->getRow() === $this->row;
    }

    public function getCol(): int {
        return $this->col;
    }

    public function getRow(): int {
        return $this->row;
    }
    
    public function getCenter(): Point {
        return $this->center;
    }

    public function frontRight(): Region {
        return $this->front()->right();
    }
    
    public function front(): Region {
        return $this->mapper->getRegion(max($this->col + 1, 0), $this->row);
    }

    public function frontLeft(): Region {
        return $this->front()->left();
    }

    public function backRight(): Region {
        return $this->back()->right();
    }
    
    public function back(): Region {
        return $this->mapper->getRegion(max($this->col - 1, 0), $this->row);
    }

    public function backLeft(): Region {
        return $this->back()->left();
    }

    public function left(): Region {
        return $this->mapper->getRegion($this->col, max($this->row + 1, 0));
    }

    public function right(): Region {
        return $this->mapper->getRegion($this->col, max($this->row - 1, 0));
    }

    public function coordinates(): Point
    {
        return new Point($this->col, $this->row);
    }

    public function distanceToRegion(IRegion $region): float
    {
        return $this->coordinates()->distanceTo($region->coordinates());
    }

    public function distanceToPoint(Point $point): float 
    {
        return $this->getCenter()->distanceTo($point);
    }

    public function containsPlayer(Player $player): bool
    {
        return $this->mapper->getRegionFromPoint($player->getPosition())->is($this);
    }

    public function __toString(): string {
        return "[{$this->col}, {$this->row}]";
    }
}