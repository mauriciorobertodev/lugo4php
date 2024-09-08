<?php

namespace Lugo4php;

use Lugo4php\Interfaces\IPositionable;
use Lugo4php\Traits\IsPositionable;

class Vector2D implements IPositionable
{
    use IsPositionable;

    public function __construct(float $x = 0, float $y = 0)
    {
        $this->x = $x;
        $this->y = $y;
    }
}