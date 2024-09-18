<?php

use Lugo4php\SPECS;
use Lugo\Point as LugoPoint;
use Lugo\Vector as LugoVector;
use Tests\Classes\Positionable;

if (!function_exists('randomPositionable')) {
    function randomPositionable(): Positionable
    {
        $p = new Positionable();
		$p->setX(rand(0, SPECS::FIELD_WIDTH));
		$p->setY(rand(0, SPECS::FIELD_HEIGHT));
		return $p;
    }
}

if (!function_exists('randomLugoPoint')) {
    function randomLugoPoint(): LugoPoint
    {
        $p = new LugoPoint();
		$p->setX(rand(0, SPECS::FIELD_WIDTH));
		$p->setY(rand(0, SPECS::FIELD_HEIGHT));
		return $p;
    }
}

if (!function_exists('randomLugoVector')) {
    function randomLugoVector(): LugoVector
    {
        $p = new LugoVector();
		$p->setX(rand(0, SPECS::FIELD_WIDTH));
		$p->setY(rand(0, SPECS::FIELD_HEIGHT));
		return $p;
    }
}