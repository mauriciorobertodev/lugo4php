<?php

use Lugo4php\Ball;
use Lugo4php\Mapper;
use Lugo4php\Player;
use Lugo4php\Point;
use Lugo4php\Velocity;
use Lugo4php\Side;
use Lugo4php\SPECS;
use Lugo4php\Vector2D;
use Lugo\Ball as LugoBall;

beforeEach(function () {
    // Configuração inicial para os testes
    $this->position = new Point(10, 20);
    $this->velocity = new Velocity( new Vector2D(1, 0), 5);
    $this->holder = new Player(1, false, Side::HOME, $this->position, $this->position, $this->velocity);
    $this->ball = new Ball($this->position, $this->velocity, $this->holder);
});

test('DEVE ser construído com valore correto', function () {
    expect($this->ball->getPosition())->toBeInstanceOf(Point::class);
    expect($this->ball->getVelocity())->toBeInstanceOf(Velocity::class);
    expect($this->ball->getHolder())->toBeInstanceOf(Player::class);
});

test('DEVE retornar a posição', function () {
    $newPosition = new Point(15, 25);
    $this->ball->setPosition($newPosition);

    expect($this->ball->getPosition())->toBe($newPosition);
});

test('DEVE retornar a Velocity', function () {
    $newVelocity = new Velocity( new Vector2D(0, 1),10);
    $this->ball->setVelocity($newVelocity);

    expect($this->ball->getVelocity())->toBe($newVelocity);
});

test('DEVE retornar a direção e velocidade', function () {
    expect($this->ball->getDirection())->toBeInstanceOf(Vector2D::class);
    expect($this->ball->getSpeed())->toBe(5.0);
});

test('DEVE retornar se existe um holder', function () {
    expect($this->ball->hasHolder())->toBeTrue();

    $ballWithoutHolder = new Ball($this->position, $this->velocity, null);
    expect($ballWithoutHolder->hasHolder())->toBeFalse();
});

test('DEVE retornar e o holder é o player X', function () {
    $anotherPlayer = new Player(2, false, Side::AWAY, $this->position, $this->position, $this->velocity);
    
    expect($this->ball->holderIs($this->holder))->toBeTrue();
    expect($this->ball->holderIs($anotherPlayer))->toBeFalse();
});

test('DEVE retornar o holder', function () {
    expect($this->ball->getHolder())->toBe($this->holder);
});

test('DEVE converter para um LugoBall', function () {
    $lugoBall = $this->ball->toLugoBall();

    expect($lugoBall)->toBeInstanceOf(LugoBall::class);
    expect($lugoBall->getHolder()->getNumber())->toBe($this->holder->getNumber());
});

test('DEVE criar uma instância com base em um LugoBall', function () {
    $lugoBall = new LugoBall();
    $lugoBall->setPosition($this->position->toLugoPoint());
    $lugoBall->setVelocity($this->velocity->toLugoVelocity());
    $lugoBall->setHolder($this->holder->toLugoPlayer());

    $ballFromLugo = Ball::fromLugoBall($lugoBall);

    expect($ballFromLugo->getPosition())->toBeInstanceOf(Point::class);
    expect($ballFromLugo->getVelocity())->toBeInstanceOf(Velocity::class);
    expect($ballFromLugo->getHolder())->toBeInstanceOf(Player::class);
});

test('DEVE retornar a direção e distancia para um player', function () {
    $player2 = randomPlayer();
    $ball = Ball::fromLugoBall(randomLugoBall());

    $direction =  $ball->directionToPlayer($player2);
    $distance = $ball->distanceToPlayer($player2);

    expect($direction)->toEqual($ball->getPosition()->directionTo($player2->getPosition()));
    expect($distance)->toEqual($ball->getPosition()->distanceTo($player2->getPosition()));
});

test('DEVE retornar a direção e distancia para um ponto', function () {
    $point = randomPoint();
    $ball = Ball::fromLugoBall(randomLugoBall());

    $direction =  $ball->directionToPoint($point);
    $distance = $ball->distanceToPoint($point);

    expect($direction)->toEqual($ball->getPosition()->directionTo($point));
    expect($distance)->toEqual($ball->getPosition()->distanceTo($point));
});

test('DEVE retornar a direção e distancia para uma região', function () {
    $mapper = new Mapper(10, 10, Side::HOME);
    $region = $mapper->getRandomRegion();
    $ball = Ball::fromLugoBall(randomLugoBall());
    

    $direction =  $ball->directionToRegion($region);
    $distance = $ball->distanceToRegion($region);

    expect($direction)->toEqual($ball->getPosition()->directionTo($region->getCenter()));
    expect($distance)->toEqual($ball->getPosition()->distanceTo($region->getCenter()));
});

test('DEVE criar uma bola no posição no centro do campo e velocity zerada', function () {
    $ball = Ball::newZeroed();
    
    expect($ball->getPosition())->toEqual(new Point(SPECS::FIELD_CENTER_X, SPECS::FIELD_CENTER_Y));
    expect($ball->getDirection())->toEqual(new Vector2D(0, 0));
    expect($ball->getSpeed())->toEqual(0);
});

