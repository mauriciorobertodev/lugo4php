<?php

use Lugo4php\Point;
use Lugo4php\SPECS;
use Lugo4php\Vector2D;
use Lugo4php\Velocity;

if (!function_exists('benchmark')) {
    function benchmark($start = null)
    {
        if ($start === null) {
            return microtime(true);
        }
        return round(microtime(true) - $start, 2) . ' seconds';
    }
}

if (!function_exists('randomPoint')) {
    function randomPoint()
    {
        return new Point(
            rand(0, SPECS::MAX_X_COORDINATE),
            rand(0, SPECS::MAX_Y_COORDINATE),
        );
    }
}

if (!function_exists('randomDirection')) {
    function randomDirection()
    {
        return (new Vector2D(
            rand(0, SPECS::MAX_X_COORDINATE),
            rand(0, SPECS::MAX_Y_COORDINATE),
        ))->normalize();
    }
}

if (!function_exists('randomVelocity')) {
    function randomVelocity(float $maxSpeed = SPECS::BALL_MAX_SPEED)
    {
        return new Velocity(
            randomDirection(),
            rand(0, $maxSpeed),
        );
    }
}


