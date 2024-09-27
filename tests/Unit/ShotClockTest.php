<?php

use Lugo4php\ShotClock;
use Lugo4php\Side;
use Lugo4php\SPECS;
use Lugo\ShotClock as LugoShotClock;

it('DEVE retornar o número correto de turnos restantes com a bola', function () {
    $shotClock = new ShotClock(Side::HOME, 10);

    expect($shotClock->getRemainingTurnsWithBall())->toBe(10);
});

it('DEVE retornar o número correto de turnos passados com a bola', function () {
    $shotClock = new ShotClock(Side::HOME, 5);

    expect($shotClock->getTurnsWithBall())->toBe(SPECS::SHOT_CLOCK_TIME - 5);
});

it('DEVE retornar o lado correto do detentor da bola', function () {
    $shotClock = new ShotClock(Side::HOME, 10);

    expect($shotClock->getHolderSide())->toBe(Side::HOME);
});

it('DEVE criar um ShotClock corretamente a partir de um LugoShotClock', function () {
    $lugoShotClock = new LugoShotClock();
	$lugoShotClock->setRemainingTurns(8);
	$lugoShotClock->setTeamSide(1);
    $shotClock = ShotClock::fromLugoShotClock($lugoShotClock);

    expect($shotClock->getRemainingTurnsWithBall())->toBe(8);
    expect($shotClock->getHolderSide())->toBe(Side::AWAY);
});
