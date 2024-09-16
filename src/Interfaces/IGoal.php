<?php

namespace Lugo4php\Interfaces;

use Lugo4php\Point;
use Lugo4php\Side;

interface IGoal {
    public function getCenter(): Point;
    public function getPlace(): Side;
    public function getTopPole(): Point;
    public function getBottomPole(): Point;
	public static function HOME(): IGoal;
	public static function AWAY(): IGoal;
}
