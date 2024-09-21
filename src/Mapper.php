<?php
namespace Lugo4php;

use InvalidArgumentException;
use Lugo4php\Side;
use Lugo4php\Interfaces\IMapper;
use Lugo4php\Interfaces\IRegion;
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
            throw new \InvalidArgumentException("Número de colunas abaixo do mínimo permitido");
        }

        if ($cols > 200) {
            throw new \InvalidArgumentException("Número de colunas acima do máximo permitido");
        }

        if ($rows < 2) {
            throw new \InvalidArgumentException("Número de linhas abaixo do mínimo permitido");
        }
        
        if ($rows > 100) {
            throw new \InvalidArgumentException("Número de linhas acima do máximo permitido");
        }

        $this->regionWidth = SPECS::FIELD_WIDTH / $cols;
        $this->regionHeight = SPECS::FIELD_HEIGHT / $rows;
    }

    public function getCols(): int
    {
        return $this->cols;
    }

    public function setCols(int $cols): self
    {
        $this->cols = $cols;
        $this->regionWidth = SPECS::FIELD_WIDTH / $cols;

        return $this;
    }

    public function getRows(): int
    {
        return $this->rows;
    }

    public function setRows(int $rows): self
    {
        $this->rows = $rows;
        $this->regionHeight = SPECS::FIELD_HEIGHT / $rows;
        return $this;
    }

    public function getSide(): Side
    {
        return $this->side;
    }
    
    public function getRegion(int $col, int $row): Region {
        if($col < 0 || $row < 0) {
            throw new InvalidArgumentException(sprintf("As regiões do campo partem do 0x0", $this->cols));
        }

        if($col > $this->cols) {
            throw new InvalidArgumentException(sprintf("O campo foi mapeado até %s colunas", $this->cols));
        }
        
        if($row > $this->rows) {
            throw new InvalidArgumentException(sprintf("O campo foi mapeado até %s linhas", $this->rows));
        }

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
    
    public function getRegionWidth(): float
    {
        return $this->regionWidth;
    }

    public function getRegionHeight(): float
    {
        return $this->regionHeight;
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

    public function getRandomRegion(): Region
    {
        return $this->getRegion(rand(0, $this->cols),rand(0, $this->rows));
    }

    private function mirrorCoordsToAway(Point $center): Point {
        $mirrored = new Point();
        $mirrored->setX(SPECS::FIELD_WIDTH - $center->getX());
        $mirrored->setY(SPECS::FIELD_HEIGHT - $center->getY());
        return $mirrored;
    }
}