<?php
namespace Lugo4php;

use Lugo4php\Side;
use Lugo4php\Interfaces\IMapper;
use Lugo4php\Point;
use Lugo4php\SPECS;

class Mapper implements IMapper {
    private float $regionWidth;
    private float $regionHeight;

    public function __construct(
        private int $cols, 
        private int $rows, 
        private Side $side
    ) {
        if ($cols < 4) {
            throw new \Exception("number of cols lower the minimum");
        }
        if ($cols > 200) {
            throw new \Exception("number of cols higher the maximum");
        }
        if ($rows < 2) {
            throw new \Exception("number of rows lower the minimum");
        }
        if ($rows > 100) {
            throw new \Exception("number of rows higher the maximum");
        }

        $this->regionWidth = SPECS::MAX_X_COORDINATE / $cols;
        $this->regionHeight = SPECS::MAX_Y_COORDINATE / $rows;
    }

    public function getRegion(int $col, int $row): Region {
        $col = max(0, min($this->cols - 1, $col));
        $row = max(0, min($this->rows - 1, $row));

        $center = new Point();
        $center->setX(round(($col * $this->regionWidth) + ($this->regionWidth / 2)));
        $center->setY(round(($row * $this->regionHeight) + ($this->regionHeight / 2)));

        if ($this->side === Side::AWAY) {
            $center = $this->mirrorCoordsToAway($center);
        }

        return new Region($col, $row, $this->side, $center, $this);
    }

    public function getRegionFromPoint(Point $point): Region {
        if ($this->side === Side::AWAY) {
            $point = $this->mirrorCoordsToAway($point);
        }

        $cx = floor($point->getX() / $this->regionWidth);
        $cy = floor($point->getY() / $this->regionHeight);

        $col = min($cx, $this->cols - 1);
        $row = min($cy, $this->rows - 1);

        return $this->getRegion($col, $row);
    }

    private function mirrorCoordsToAway(Point $center): Point {
        $mirrored = new Point();
        $mirrored->setX(SPECS::MAX_X_COORDINATE - $center->getX());
        $mirrored->setY(SPECS::MAX_Y_COORDINATE - $center->getY());
        return $mirrored;
    }
}