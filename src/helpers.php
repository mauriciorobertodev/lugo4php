<?php

use Lugo4php\Point;

if (!function_exists('dd')) {
    function dd(...$vars)
    {
        foreach ($vars as $var) {
            var_dump($var);
        }
        die();
    }
}

if (!function_exists('getDistanceBetween')) {
    function getDistanceBetween(Point $a, Point $b): float
    {
        return sqrt(pow($a->getX() - $b->getX(), 2) + pow($a->getY() - $b->getY(), 2));
    }
}

function add(Point $a, Point $b): Point
{
    return (new Point())->setX($a->getX() + $b->getX())->setY($a->getY() + $b->getY());
}

function addY(Point $v, float $value): Point
{
    return (new Point())->setX($v->getX())->setY($v->getY() + $value);
}

function addX(Point $v, float $value): Point
{
    return (new Point())->setX($v->getX() + $value)->setY($v->getY());
}

function sub(Point $a, Point $b): Point
{
    return (new Point())->setX($a->getX() - $b->getX())->setY($a->getY() - $b->getY());
}

function subY(Point $v, float $value): Point
{
    return (new Point())->setX($v->getX())->setY($v->getY() - $value);
}

function subX(Point $v, float $value): Point
{
    return (new Point())->setX($v->getX() - $value)->setY($v->getY());
}

function mag(Point $v): float
{
    return sqrt($v->getX() * $v->getX() + $v->getY() * $v->getY());
}

function scale(Point $v, float $scalar): Point
{
    return (new Point())->setX($v->getX() * $scalar)->setY($v->getY() * $scalar);
}

function mult(Point $v, float $scalar): Point
{
    return scale($v, $scalar);
}

function div(Point $v, float $scalar): Point
{
    return (new Point())->setX($v->getX() / $scalar)->setY($v->getY() / $scalar);
}

function normalize(Point $p): Point
{
    $magnitude = mag($p);
    return div($p, $magnitude);
}

function getDirectionTo(Point $from, Point $to): Point
{
    $v = new Point();
    $v->setX($to->getX() - $from->getX());
    $v->setY($to->getY() - $from->getY());

    return normalize($v);
}

function targetFrom(Point $direction, Point $point): Point
{
    $target = new Point();
    
    $target->setX($point->getX() + round($direction->getX()));
    $target->setY($point->getY() + round($direction->getY()));
    
    return $target;
}

