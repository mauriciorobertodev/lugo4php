<?php

use Lugo4php\Mapper;
use Lugo4php\Player;
use Lugo4php\Side;
use Lugo4php\Point;
use Lugo4php\Velocity;
use Lugo4php\Vector2D;
use Lugo4php\SPECS;
use Lugo\Player as LugoPlayer;

test('DEVE criar um Player com os atributos corretos', function () {
    $side = Side::HOME;
    $position = new Point(10, 20); // Supondo que Point aceita esses valores
    $initPosition = new Point(5, 10);
    $velocity = new Velocity( new Vector2D(1, 0), 30.0); // Supondo que Velocity e Vector2D aceitam esses valores

    $player = new Player(7, true, $side, $position, $initPosition, $velocity);

    expect($player->getNumber())->toBe(7);
    expect($player->getSpeed())->toBe(30.0);
    expect($player->getDirection())->toEqual(new Vector2D(1, 0));
    expect($player->getPosition())->toEqual($position);
    expect($player->getVelocity())->toEqual($velocity);
    expect($player->getTeamSide())->toBe($side);
    expect($player->getInitPosition())->toEqual($initPosition);
    expect($player->getIsJumping())->toBeTrue();
});

test('DEVE verificar se o jogador é o goleiro com base no número', function () {
    $side = Side::AWAY;
    $position = new Point(10, 20);
    $initPosition = new Point(5, 10);
    $velocity = new Velocity( new Vector2D(1, 0), 30.0);

    $goalkeeper = new Player(SPECS::GOALKEEPER_NUMBER, false, $side, $position, $initPosition, $velocity);
    $nonGoalkeeper = new Player(8, false, $side, $position, $initPosition, $velocity);

    expect($goalkeeper->isGoalkeeper())->toBeTrue();
    expect($nonGoalkeeper->isGoalkeeper())->toBeFalse();
});

test('DEVE verificar a igualdade entre jogadores', function () {
    $side = Side::HOME;
    $position = new Point(10, 20);
    $initPosition = new Point(5, 10);
    $velocity = new Velocity( new Vector2D(1, 0),30.0);

    $player1 = new Player(7, true, $side, $position, $initPosition, $velocity);
    $player2 = new Player(7, true, $side, $position, $initPosition, $velocity);
    $player3 = new Player(8, true, $side, $position, $initPosition, $velocity);

    expect($player1->eq($player2))->toBeTrue();
    expect($player1->is($player2))->toBeTrue();
    expect($player1->eq($player3))->toBeFalse();
    expect($player1->is($player3))->toBeFalse();
});

test('DEVE converter um Player para LugoPlayer e vice-versa', function () {
    $side = Side::AWAY;
    $position = new Point(10, 20);
    $initPosition = new Point(5, 10);
    $velocity = new Velocity( new Vector2D(1, 0), 30.0);

    $player = new Player(7, true, $side, $position, $initPosition, $velocity);
    $lugoPlayer = $player->toLugoPlayer();

	expect($lugoPlayer)->toBeInstanceOf(LugoPlayer::class);
    $convertedPlayer = Player::fromLugoPlayer($lugoPlayer);
	
	expect($convertedPlayer)->toBeInstanceOf(Player::class);
    expect($convertedPlayer->getNumber())->toBe($player->getNumber());
    expect($convertedPlayer->getSpeed())->toBe($player->getSpeed());
    expect($convertedPlayer->getDirection())->toEqual($player->getDirection());
    expect($convertedPlayer->getPosition())->toEqual($player->getPosition());
    expect($convertedPlayer->getVelocity())->toEqual($player->getVelocity());
    expect($convertedPlayer->getTeamSide())->toBe($player->getTeamSide());
    expect($convertedPlayer->getInitPosition())->toEqual($player->getInitPosition());
    expect($convertedPlayer->getIsJumping())->toBe($player->getIsJumping());
});

test('DEVE retornar corretamente se está no campo de defesa ou ataque', function () {
    $side = Side::HOME;
    $position = new Point(10, 20);
    $player = new Player(7, true, $side, $position, randomPoint(), randomVelocity());
    expect($player->isInAttackSide())->toBeFalse();
    expect($player->isInDefenseSide())->toBeTrue();

    $side = Side::AWAY;
    $position = new Point(10, 20);
    $player = new Player(7, true, $side, $position, randomPoint(), randomVelocity());
    expect($player->isInAttackSide())->toBeTrue();
    expect($player->isInDefenseSide())->toBeFalse();
});

test('DEVE retornar a direção e distancia para um player', function () {
    $side = Side::HOME;
    $position = new Point(10, 20);
    $player = new Player(7, true, $side, $position, randomPoint(), randomVelocity());
    $player2 = randomPlayer();

    $direction =  $player->directionToPlayer($player2);
    $distance = $player->distanceToPlayer($player2);

    expect($direction)->toEqual($player->getPosition()->directionTo($player2->getPosition()));
    expect($distance)->toEqual($player->getPosition()->distanceTo($player2->getPosition()));
});

test('DEVE retornar a direção e distancia para um ponto', function () {
    $side = Side::HOME;
    $position = new Point(10, 20);
    $player = new Player(7, true, $side, $position, randomPoint(), randomVelocity());
    $point = randomPoint();

    $direction =  $player->directionToPoint($point);
    $distance = $player->distanceToPoint($point);

    expect($direction)->toEqual($player->getPosition()->directionTo($point));
    expect($distance)->toEqual($player->getPosition()->distanceTo($point));
});

test('DEVE retornar a direção e distancia para uma região', function () {
    $side = Side::HOME;
    $position = new Point(10, 20);
    $player = new Player(7, true, $side, $position, randomPoint(), randomVelocity());
    $mapper = new Mapper(10, 10, Side::HOME);
    $region = $mapper->getRandomRegion();

    $direction =  $player->directionToRegion($region);
    $distance = $player->distanceToRegion($region);

    expect($direction)->toEqual($player->getPosition()->directionTo($region->getCenter()));
    expect($distance)->toEqual($player->getPosition()->distanceTo($region->getCenter()));
});
