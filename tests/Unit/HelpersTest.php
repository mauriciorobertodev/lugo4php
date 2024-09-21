<?php

use Lugo4php\PlayerState;
use Lugo4php\Point;
use Lugo4php\Side;
use Lugo4php\SPECS;
use Lugo4php\Vector2D;
use Lugo\Player as LugoPlayer;
use Lugo\Point as LugoPoint;
use Lugo\Team_Side;
use Lugo\Vector as LugoVector;

test('DEVE gerar um LugoPoint e um LugoVector aleatório dentro do campo', function() {
	for ($i=0; $i < 100; $i++) { 
		$p = randomLugoPoint();
		
		expect($p)->toBeInstanceOf(LugoPoint::class);
		expect($p->getX())->toBeLessThanOrEqual(SPECS::FIELD_WIDTH);
		expect($p->getY())->toBeLessThanOrEqual(SPECS::FIELD_HEIGHT);
		expect($p->getX())->toBeGreaterThanOrEqual(0);
		expect($p->getY())->toBeGreaterThanOrEqual(0);
	}
	
	for ($i=0; $i < 100; $i++) { 
		$v = randomLugoVector();

		expect($v)->toBeInstanceOf(LugoVector::class);
		expect($v->getX())->toBeLessThanOrEqual(SPECS::FIELD_WIDTH);
		expect($v->getY())->toBeLessThanOrEqual(SPECS::FIELD_HEIGHT);
		expect($v->getX())->toBeGreaterThanOrEqual(0);
		expect($v->getY())->toBeGreaterThanOrEqual(0);
	}
});

test('DEVE gerar um LugoPlayer aleatório', function() {
	$p = randomLugoPlayer(10, null, null, null, Team_Side::HOME);

	expect($p)->toBeInstanceOf(LugoPlayer::class);
	expect($p->getNumber())->toEqual(10);
	expect($p->getTeamSide())->toBe(Team_Side::HOME);
	expect($p->getPosition()->getX())->toBeLessThanOrEqual(SPECS::FIELD_WIDTH);
	expect($p->getPosition()->getY())->toBeLessThanOrEqual(SPECS::FIELD_HEIGHT);
	expect($p->getPosition()->getX())->toBeGreaterThanOrEqual(0);
	expect($p->getPosition()->getY())->toBeGreaterThanOrEqual(0);
});

test('DEVE gerar um Point e um Vector2D aleatório dentro do campo', function() {
	for ($i=0; $i < 100; $i++) { 
		$p = randomPoint();
		
		expect($p)->toBeInstanceOf(Point::class);
		expect($p->getX())->toBeLessThanOrEqual(SPECS::FIELD_WIDTH);
		expect($p->getY())->toBeLessThanOrEqual(SPECS::FIELD_HEIGHT);
		expect($p->getX())->toBeGreaterThanOrEqual(0);
		expect($p->getY())->toBeGreaterThanOrEqual(0);
	}
	
	for ($i=0; $i < 100; $i++) { 
		$v = randomDirection();

		expect($v)->toBeInstanceOf(Vector2D::class);
		expect($v->getX())->toBeLessThanOrEqual(SPECS::FIELD_WIDTH);
		expect($v->getY())->toBeLessThanOrEqual(SPECS::FIELD_HEIGHT);
		expect($v->getX())->toBeGreaterThanOrEqual(0);
		expect($v->getY())->toBeGreaterThanOrEqual(0);
	}
});

test('DEVE retornar o tempo passado sem segundos corretamente', function () {
    $start = benchmark();

    usleep(50000); // 50ms
    
    $elapsed = benchmark($start);
    
    expect($elapsed)->toContain('seconds');
});


