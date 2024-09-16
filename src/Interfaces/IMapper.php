<?php
namespace Lugo4php\Interfaces;

use Lugo4php\Point;
use Lugo4php\Side;

interface IMapper
{
    public function getCols(): int;
    public function setCols(int $cols): self;
    public function getRows(): int;
    public function getRegionWidth(): int;
    public function getRegionHeight(): int;
    public function setRows(int $rows): self;
    public function getSide(): Side;
    public function getRegion(int $col, int $row): IRegion;
    public function getRegionFromPoint(Point $point): IRegion;
    public function getRandomRegion(): IRegion;
}