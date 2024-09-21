<?php

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
    expect($player->getSide())->toBe($side);
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
    expect($convertedPlayer->getSide())->toBe($player->getSide());
    expect($convertedPlayer->getInitPosition())->toEqual($player->getInitPosition());
    expect($convertedPlayer->getIsJumping())->toBe($player->getIsJumping());
});
