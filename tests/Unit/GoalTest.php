<?php

use Lugo4php\Goal;
use Lugo4php\Point;
use Lugo4php\Side;
use Lugo4php\SPECS;

test('DEVE criar um gol para o lado HOME', function () {
    $goal = Goal::HOME();

    expect($goal->getPlace())->toBe(Side::HOME);
    expect($goal->getCenter())->toEqual(new Point(0, SPECS::MAX_Y_COORDINATE / 2));
    expect($goal->getTopPole())->toEqual(new Point(0, SPECS::GOAL_MAX_Y));
    expect($goal->getBottomPole())->toEqual(new Point(0, SPECS::GOAL_MIN_Y));
});

test('DEVE criar um gol para o lado AWAY', function () {
    $goal = Goal::AWAY();

    expect($goal->getPlace())->toBe(Side::AWAY);
    expect($goal->getCenter())->toEqual(new Point(SPECS::MAX_X_COORDINATE, SPECS::MAX_Y_COORDINATE / 2));
    expect($goal->getTopPole())->toEqual(new Point(SPECS::MAX_X_COORDINATE, SPECS::GOAL_MAX_Y));
    expect($goal->getBottomPole())->toEqual(new Point(SPECS::MAX_X_COORDINATE, SPECS::GOAL_MIN_Y));
});

test('DEVE construir um gol customizado', function () {
    $center = new Point(10, 20);
    $topPole = new Point(10, 30);
    $bottomPole = new Point(10, 10);
    $goal = new Goal(Side::HOME, $center, $topPole, $bottomPole);

    expect($goal->getPlace())->toBe(Side::HOME);
    expect($goal->getCenter())->toEqual($center);
    expect($goal->getTopPole())->toEqual($topPole);
    expect($goal->getBottomPole())->toEqual($bottomPole);
});
