<?php

namespace Lugo4php;

use Lugo4php\Interfaces\IGoal;
use Lugo4php\Side;
use Lugo4php\Point;

class Goal implements IGoal {
    public function __construct(
        protected Side $place, 
        protected Point $center, 
        protected Point $topPole, 
        protected Point $bottomPole
    ) {}

    public function getCenter(): Point {
        return $this->center;
    }

    public function getPlace(): Side {
        return $this->place;
    }

    public function getTopPole(): Point {
        return $this->topPole;
    }

    public function getBottomPole(): Point {
        return $this->bottomPole;
    }

    public static function HOME(): Goal
    {
        return  new Goal(
            Side::HOME,
            new Point(0, SPECS::MAX_Y_COORDINATE / 2),
            new Point(0, SPECS::GOAL_MAX_Y),
            new Point(0, SPECS::GOAL_MIN_Y),
        );
    }

    public static function AWAY(): Goal
    {
        return  new Goal(
            Side::AWAY,
            new Point(SPECS::MAX_X_COORDINATE, SPECS::MAX_Y_COORDINATE / 2),
            new Point(SPECS::MAX_X_COORDINATE, SPECS::GOAL_MAX_Y),
            new Point(SPECS::MAX_X_COORDINATE, SPECS::GOAL_MIN_Y),
        );
    }
}
