<?php
namespace Lugo4php\Interfaces;

use Lugo4php\Point;

interface IMapper
{
    public function getRegion(int $col, int $row): IRegion;
    public function getRegionFromPoint(Point $point): IRegion;
}