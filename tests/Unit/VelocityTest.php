<?php

use Lugo4php\Vector2D;
use Lugo4php\Velocity;
use Lugo\Vector;
use Lugo\Velocity as LugoVelocity;

test("DEVE retornar uma velocity zerada", function() {
	$velocity = Velocity::newZeroed();

	expect($velocity->getDirection()->is(new Vector2D(0,0)))->toBeTrue();
});

test("DEVE retornar direção e velocidade ao ser usado em uma string", function() {
	$velocity = new Velocity(new Vector2D(555.5, 666.6), 222);
	$string = sprintf("%s", $velocity);

	expect($string)->toBe("[555.5, 666.6, 222]");
});

test("DEVE retornar uma nova Velocity com base em uma LugoVelocity", function() {
	$lugoVelocity = new LugoVelocity();
	$direction = new Vector();
	$direction->setX(0.80);
	$direction->setY(0.60);
	$lugoVelocity->setDirection($direction);
	$lugoVelocity->setSpeed(777.88);

	$velocity = Velocity::fromLugoVelocity($lugoVelocity);
	$expectedDirection = new Vector2D(0.80, 0.60);
	$expectedSpeed = 777.88;

	expect($velocity->getDirection()->getX())->toEqual($expectedDirection->getX());
	expect($velocity->getDirection()->getY())->toEqual($expectedDirection->getY());
	expect($velocity->getSpeed())->toEqual($expectedSpeed);
});

test("DEVE retornar uma LugoVelocity", function() {
	$expectedVelocity = new LugoVelocity();
	$direction = new Vector();
	$direction->setX(0.80);
	$direction->setY(0.60);
	$expectedVelocity->setDirection($direction);
	$expectedVelocity->setSpeed(777.88);

	$velocity = new Velocity(new Vector2D(0.80, 0.60), 777.88);

	$lugoVelocity = $velocity->toLugoVelocity();

	expect($lugoVelocity)->toBeInstanceOf(LugoVelocity::class);
	expect($lugoVelocity->getDirection())->toEqual($expectedVelocity->getDirection());
	expect($lugoVelocity->getSpeed())->toEqual($expectedVelocity->getSpeed());
});

test("DEVE definir a direção e velocidade", function() {
	$velocity = Velocity::newZeroed();

	expect($velocity->getDirection())->toEqual(new Vector2D(0,0));
	expect($velocity->getSpeed())->toEqual(0);

	$velocity->setSpeed(888.8);
	$velocity->setDirection(new Vector2D(111.1, 222.2));
	expect($velocity->getDirection())->toEqual(new Vector2D(111.1, 222.2));
	expect($velocity->getSpeed())->toEqual(888.8);
});