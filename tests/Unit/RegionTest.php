<?php

use Lugo4php\Mapper;
use Lugo4php\Player;
use Lugo4php\Point;
use Lugo4php\Side;
use Lugo4php\Vector2D;
use Lugo4php\Velocity;

test('DEVE retornar a coluna e linha da região', function() {
	$mapper = new Mapper(10, 10, Side::HOME);
	$region = $mapper->getRegion(3, 4);

	expect($region->getCol())->toEqual(3);
	expect($region->getRow())->toEqual(4);
});

test('DEVE retornar se a região é igual a outra', function() {
	$mapper = new Mapper(10, 10, Side::HOME);
	$regionA = $mapper->getRegion(3, 4);
	$regionB = $mapper->getRegion(4, 6);
	$regionC = $mapper->getRegion(3, 4);

	expect($regionA->is($regionB))->toBeFalse();
	expect($regionA->eq($regionB))->toBeFalse();

	expect($regionA->is($regionC))->toBeTrue();
	expect($regionA->eq($regionC))->toBeTrue();
	
	expect($regionB->is($regionC))->toBeFalse();
	expect($regionB->eq($regionC))->toBeFalse();
});

test('DEVE retornar o ponto central da região', function() {
	$mapper = new Mapper(10, 10, Side::HOME);

	$region = $mapper->getRegion(0, 0);
	$center = $region->getCenter();
	expect($center->getX())->toEqual(1000);
	expect($center->getY())->toEqual(500);

	$region = $mapper->getRegion(4, 4);
	$center = $region->getCenter();
	expect($center->getX())->toEqual(9000);
	expect($center->getY())->toEqual(4500);

	$mapper = new Mapper(10, 10, Side::AWAY);

	$region = $mapper->getRegion(0, 0);
	$center = $region->getCenter();
	expect($center->getX())->toEqual(19000);
	expect($center->getY())->toEqual(9500);

	$region = $mapper->getRegion(4, 4);
	$center = $region->getCenter();
	expect($center->getX())->toEqual(11000);
	expect($center->getY())->toEqual(5500);
});

test('DEVE retornar as regiões ao redor', function() {
	$mapper = new Mapper(10, 10, Side::HOME);

	$region = $mapper->getRegion(5, 5);

	$front = $region->front();
	expect($front->getCol())->toEqual(6);
	expect($front->getRow())->toEqual(5);

	$back = $region->back();
	expect($back->getCol())->toEqual(4);
	expect($back->getRow())->toEqual(5);

	$left = $region->left();
	expect($left->getCol())->toEqual(5);
	expect($left->getRow())->toEqual(4);

	$right = $region->right();
	expect($right->getCol())->toEqual(5);
	expect($right->getRow())->toEqual(6);

	$frontRight = $region->frontRight();
	expect($frontRight->getCol())->toEqual(6);
	expect($frontRight->getRow())->toEqual(6);

	$frontLeft = $region->frontLeft();
	expect($frontLeft->getCol())->toEqual(6);
	expect($frontLeft->getRow())->toEqual(4);

	$backRight = $region->backRight();
	expect($backRight->getCol())->toEqual(4);
	expect($backRight->getRow())->toEqual(6);

	$backLeft = $region->backLeft();
	expect($backLeft->getCol())->toEqual(4);
	expect($backLeft->getRow())->toEqual(4);
});

test('DEVE um Point com as coordenadas da região', function() {
	$mapper = new Mapper(10, 10, Side::HOME);
	$region = $mapper->getRandomRegion();
	$point = $region->coordinates();
	
	expect($region->getCol())->toEqual($point->getX());
	expect($region->getRow())->toEqual($point->getY());
});

test('DEVE retornar as coordenadas quando for usado em string', function() {
	$mapper = new Mapper(10, 10, Side::HOME);
	$region = $mapper->getRegion(7, 8);
	$string = sprintf("%s", $region);

	expect($string)->toEqual("[7, 8]");
});

test('DEVE retornar a distância entre regiões', function() {
	$mapper = new Mapper(10, 10, Side::HOME);

	$regionA = $mapper->getRegion(0, 0);
	$regionB = $mapper->getRegion(3, 4);
	expect($regionA->distanceToRegion($regionB))->toEqual(5);

	$regionA = $mapper->getRegion(1, 6);
	$regionB = $mapper->getRegion(5, 9);
	expect($regionA->distanceToRegion($regionB))->toEqual(5);

	$regionA = $mapper->getRegion(7, 2);
	$regionB = $mapper->getRegion(3, 5);
	expect($regionA->distanceToRegion($regionB))->toEqual(5);

	$mapper = new Mapper(10, 10, Side::AWAY);

	$regionA = $mapper->getRegion(0, 0);
	$regionB = $mapper->getRegion(3, 4);
	expect($regionA->distanceToRegion($regionB))->toEqual(5);

	$regionA = $mapper->getRegion(1, 6);
	$regionB = $mapper->getRegion(5, 9);
	expect($regionA->distanceToRegion($regionB))->toEqual(5);

	$regionA = $mapper->getRegion(7, 2);
	$regionB = $mapper->getRegion(3, 5);
	expect($regionA->distanceToRegion($regionB))->toEqual(5);
});

test('DEVE retornar a distância entre o centro da região e um ponto', function() {
	$mapper = new Mapper(10, 10, Side::HOME);

	$regionA = $mapper->getRegion(0, 0);
	$regionB = $mapper->getRegion(4, 4);
	expect($regionA->distanceToPoint($regionB->getCenter()))->toEqual(8944.27190999916);

	$regionA = $mapper->getRegion(1, 6);
	$regionB = $mapper->getRegion(5, 9);
	expect($regionA->distanceToPoint($regionB->getCenter()))->toEqual(8544.003745317532);

	$regionA = $mapper->getRegion(7, 2);
	$regionB = $mapper->getRegion(3, 5);
	expect($regionA->distanceToPoint($regionB->getCenter()))->toEqual(8544.003745317532);
});

test('DEVE retornar se o player x está dentro da região', function() {
	$mapper = new Mapper(10, 10, Side::HOME);
	$player = new Player(
		1,
		false,
		Side::HOME,
		new Point(500, 600),
		new Point(),
		new Velocity(new Vector2D(), 100)
	);

	$regionA = $mapper->getRegion(0, 0);
	expect($regionA->containsPlayer($player))->toBeTrue();
	$regionB = $mapper->getRegion(4, 4);
	expect($regionB->containsPlayer($player))->toBeFalse();
});