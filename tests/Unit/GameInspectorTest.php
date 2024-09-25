<?php

use Lugo4php\GameInspector;
use Lugo4php\Goal;
use Lugo4php\Mapper;
use Lugo4php\Player;
use Lugo4php\PlayerState;
use Lugo4php\Point;
use Lugo4php\Side;
use Lugo4php\SPECS;
use Lugo4php\Team;
use Lugo4php\Vector2D;
use Lugo4php\Velocity;
use Lugo\Ball as LugoBall;
use Lugo\CatchOrder;
use Lugo\Player as LugoPlayer;
use Lugo\GameSnapshot;
use Lugo\JumpOrder;
use Lugo\KickOrder;
use Lugo\MoveOrder;
use Lugo\Order;
use Lugo\Team_Side;

test('DEVE lançar exceção se o jogador não for encontrado', function () {
    $snapshot = new GameSnapshot(); // Simulação do snapshot vazio
	$snapshot->setHomeTeam(randomLugoTeam(Team_Side::HOME));
	$snapshot->setAwayTeam(randomLugoTeam(Team_Side::AWAY));
	$snapshot->getHomeTeam()->setPlayers([]);
    $botSide = Side::HOME;

    expect(fn() => new GameInspector($botSide, 1, $snapshot))
        ->toThrow(Exception::class, 'O time do lado home não tem o player 1');
});

test('DEVE retornar corretamente as propriedades do player', function () {
    $snapshot = randomLugoGameSnapshot();
	$botSide = Side::HOME;
	$botNumber = 11;
	$inspector = new GameInspector($botSide, $botNumber, $snapshot);

	$lugoMe = array_values(array_filter([...$snapshot->getHomeTeam()->getPlayers()], fn(LugoPlayer $p) => $p->getNumber() === $botNumber))[0];
	$lugoPlayer10 = array_values(array_filter([...$snapshot->getHomeTeam()->getPlayers()], fn(LugoPlayer $p) => $p->getNumber() === 10))[0];
	$lugo4phpMe = Player::fromLugoPlayer($lugoMe);
	$lugo4phpPlayer10 = Player::fromLugoPlayer($lugoPlayer10);
	
	$lugoOpponent01 = array_values(array_filter([...$snapshot->getAwayTeam()->getPlayers()], fn(LugoPlayer $p) => $p->getNumber() === 1))[0];
	$lugoOpponent10 = array_values(array_filter([...$snapshot->getAwayTeam()->getPlayers()], fn(LugoPlayer $p) => $p->getNumber() === 10))[0];
	$lugo4phpOpponent01 = Player::fromLugoPlayer($lugoOpponent01);
	$lugo4phpOpponent10 = Player::fromLugoPlayer($lugoOpponent10);

    expect($inspector->getTurn())->toEqual($snapshot->getTurn());
    expect($inspector->getSnapshot())->toEqual($snapshot);
    expect($inspector->getMe()->getNumber())->toEqual($botNumber);
    expect($inspector->getMe()->getTeamSide())->toEqual($botSide);
    expect($inspector->getMyNumber())->toEqual($botNumber);
    expect($inspector->getMySide())->toEqual($botSide);
    expect($inspector->getMyTeam())->toEqual(Team::fromLugoTeam($snapshot->getHomeTeam()));
    expect($inspector->getMyGoalkeeper()->getNumber())->toEqual(SPECS::GOALKEEPER_NUMBER);
    expect($inspector->getMyGoalkeeper()->getTeamSide())->toEqual($botSide);
    expect($inspector->getMyPosition())->toEqual($lugo4phpMe->getPosition());
    expect($inspector->getMyDirection())->toEqual($lugo4phpMe->getDirection());
    expect($inspector->getMySpeed())->toEqual($lugo4phpMe->getSpeed());
    expect($inspector->getMyVelocity())->toEqual(Velocity::fromLugoVelocity($lugoMe->getVelocity()));
    expect($inspector->getMyPlayers())->toEqual(Team::fromLugoTeam($snapshot->getHomeTeam())->getPlayers());
    expect($inspector->getMyScore())->toEqual($snapshot->getHomeTeam()->getScore());
    expect($inspector->getMyPlayer(10))->toEqual($lugo4phpPlayer10);

	expect($inspector->getOpponentGoalkeeper())->toEqual($lugo4phpOpponent01);
	expect($inspector->getOpponentPlayer(10))->toEqual($lugo4phpOpponent10);
	expect($inspector->getOpponentPlayers())->toEqual(Team::fromLugoTeam($snapshot->getAwayTeam())->getPlayers());
	expect($inspector->getOpponentScore())->toEqual($snapshot->getAwayTeam()->getScore());
	expect($inspector->getOpponentSide())->toEqual(Side::AWAY);
	expect($inspector->getOpponentTeam())->toEqual(Team::fromLugoTeam($snapshot->getAwayTeam()));

	expect($inspector->getTeam(Side::HOME))->toEqual(Team::fromLugoTeam($snapshot->getHomeTeam()));
	expect($inspector->getTeam(Side::AWAY))->toEqual(Team::fromLugoTeam($snapshot->getAwayTeam()));
	
	expect($inspector->getAttackGoal())->toEqual(Goal::AWAY());
	expect($inspector->getDefenseGoal())->toEqual(Goal::HOME());
});

test('DEVE retornar uma ordem de movimentação para um ponto X', function () {
	$botNumber = 11;
    $snapshot = randomLugoGameSnapshot();
    $inspector = new GameInspector(Side::HOME, $botNumber, $snapshot);
	$lugoMe = array_values(array_filter([...$snapshot->getHomeTeam()->getPlayers()], fn(LugoPlayer $p) => $p->getNumber() === $botNumber))[0];
	$mePosition = Point::fromLugoPoint($lugoMe->getPosition());

	$point = randomPoint();
	$order = $inspector->makeOrderMoveToPoint($point);

	expect($order)->toBeInstanceOf(Order::class);
	expect($order->getMove())->toBeInstanceOf(MoveOrder::class);
	expect($order->getMove()->getVelocity()->getDirection())->toEqual($mePosition->directionTo($point)->toLugoVector());
	expect($order->getMove()->getVelocity()->getSpeed())->toEqual(SPECS::PLAYER_MAX_SPEED);

	$order = $inspector->makeOrderMoveToPoint($point, 30);
	expect($order->getMove()->getVelocity()->getSpeed())->toEqual(30);
});

test('DEVE retornar uma ordem de chute para um ponto X', function () {
	$botNumber = 11;
    $snapshot = randomLugoGameSnapshot();
    $inspector = new GameInspector(Side::HOME, $botNumber, $snapshot);
	$lugoMe = array_values(array_filter([...$snapshot->getHomeTeam()->getPlayers()], fn(LugoPlayer $p) => $p->getNumber() === $botNumber))[0];
	$mePosition = Point::fromLugoPoint($lugoMe->getPosition());

	$point = randomPoint();
	$order = $inspector->makeOrderKickToPoint($point);

	expect($order)->toBeInstanceOf(Order::class);
	expect($order->getKick())->toBeInstanceOf(KickOrder::class);
	expect($order->getKick()->getVelocity()->getDirection())->toEqual($inspector->getBall()->getPosition()->directionTo($point)->toLugoVector());
	expect($order->getKick()->getVelocity()->getSpeed())->toEqual(SPECS::BALL_MAX_SPEED);

	$order = $inspector->makeOrderKickToPoint($point, 30);
	expect($order->getKick()->getVelocity()->getSpeed())->toEqual(30);
});

test('DEVE retornar uma ordem de movimentação para uma direção X', function () {
	$botNumber = 11;
    $snapshot = randomLugoGameSnapshot();
    $inspector = new GameInspector(Side::HOME, $botNumber, $snapshot);

	$direction = randomDirection();
	$order = $inspector->makeOrderMoveToDirection($direction);

	expect($order)->toBeInstanceOf(Order::class);
	expect($order->getMove())->toBeInstanceOf(MoveOrder::class);
	expect($order->getMove()->getVelocity()->getDirection())->toEqual($direction->toLugoVector());
	expect($order->getMove()->getVelocity()->getSpeed())->toEqual(SPECS::PLAYER_MAX_SPEED);

	$order = $inspector->makeOrderMoveToDirection($direction, 30);
	expect($order->getMove()->getVelocity()->getSpeed())->toEqual(30);
});

test('DEVE retornar uma ordem de chute para uma direção X', function () {
	$botNumber = 11;
    $snapshot = randomLugoGameSnapshot();
    $inspector = new GameInspector(Side::HOME, $botNumber, $snapshot);

	$direction = randomDirection();
	$order = $inspector->makeOrderKickToDirection($direction);

	expect($order)->toBeInstanceOf(Order::class);
	expect($order->getKick())->toBeInstanceOf(KickOrder::class);
	expect($order->getKick()->getVelocity()->getDirection())->toEqual($direction->toLugoVector());
	expect($order->getKick()->getVelocity()->getSpeed())->toEqual(SPECS::BALL_MAX_SPEED);

	$order = $inspector->makeOrderKickToDirection($direction, 30);
	expect($order->getKick()->getVelocity()->getSpeed())->toEqual(30);
});

test('DEVE retornar uma ordem de movimentação para uma região X', function () {
	$botNumber = 11;
    $snapshot = randomLugoGameSnapshot();
    $inspector = new GameInspector(Side::HOME, $botNumber, $snapshot);
	$lugoMe = array_values(array_filter([...$snapshot->getHomeTeam()->getPlayers()], fn(LugoPlayer $p) => $p->getNumber() === $botNumber))[0];
	$mePosition = Point::fromLugoPoint($lugoMe->getPosition());
	$mapper = new Mapper(10, 10, Side::HOME);
	$region = $mapper->getRandomRegion();

	$order = $inspector->makeOrderMoveToRegion($region);

	expect($order)->toBeInstanceOf(Order::class);
	expect($order->getMove())->toBeInstanceOf(MoveOrder::class);
	expect($order->getMove()->getVelocity()->getDirection())->toEqual($mePosition->directionTo($region->getCenter())->toLugoVector());
	expect($order->getMove()->getVelocity()->getSpeed())->toEqual(SPECS::PLAYER_MAX_SPEED);

	$order = $inspector->makeOrderMoveToRegion($region, 30);
	expect($order->getMove()->getVelocity()->getSpeed())->toEqual(30);
});

test('DEVE retornar uma ordem de chute para uma região X', function () {
	$botNumber = 11;
    $snapshot = randomLugoGameSnapshot();
    $inspector = new GameInspector(Side::HOME, $botNumber, $snapshot);
	$lugoMe = array_values(array_filter([...$snapshot->getHomeTeam()->getPlayers()], fn(LugoPlayer $p) => $p->getNumber() === $botNumber))[0];
	$mePosition = Point::fromLugoPoint($lugoMe->getPosition());
	$mapper = new Mapper(10, 10, Side::HOME);
	$region = $mapper->getRandomRegion();

	$order = $inspector->makeOrderKickToRegion($region);

	expect($order)->toBeInstanceOf(Order::class);
	expect($order->getKick())->toBeInstanceOf(KickOrder::class);
	expect($order->getKick()->getVelocity()->getDirection())->toEqual($mePosition->directionTo($region->getCenter())->toLugoVector());
	expect($order->getKick()->getVelocity()->getSpeed())->toEqual(SPECS::BALL_MAX_SPEED);

	$order = $inspector->makeOrderKickToRegion($region, 30);
	expect($order->getKick()->getVelocity()->getSpeed())->toEqual(30);
});


test('DEVE retornar uma ordem de movimentação para um player X', function () {
	$botNumber = 11;
    $snapshot = randomLugoGameSnapshot();
    $inspector = new GameInspector(Side::HOME, $botNumber, $snapshot);
	$lugoMe = array_values(array_filter([...$snapshot->getHomeTeam()->getPlayers()], fn(LugoPlayer $p) => $p->getNumber() === $botNumber))[0];
	$mePosition = Point::fromLugoPoint($lugoMe->getPosition());
	$randomPlayer = Player::fromLugoPlayer(array_values(array_filter([...$snapshot->getHomeTeam()->getPlayers()], fn(LugoPlayer $p) => $p->getNumber() != $botNumber))[rand(0, 9)]);

	$order = $inspector->makeOrderMoveToPlayer($randomPlayer);

	expect($order)->toBeInstanceOf(Order::class);
	expect($order->getMove())->toBeInstanceOf(MoveOrder::class);
	expect($order->getMove()->getVelocity()->getDirection())->toEqual($mePosition->directionTo($randomPlayer->getPosition())->toLugoVector());
	expect($order->getMove()->getVelocity()->getSpeed())->toEqual(SPECS::PLAYER_MAX_SPEED);

	$order = $inspector->makeOrderMoveToPlayer($randomPlayer, 30);
	expect($order->getMove()->getVelocity()->getSpeed())->toEqual(30);
});

test('DEVE retornar uma ordem de chute para um player X', function () {
	$botNumber = 11;
    $snapshot = randomLugoGameSnapshot();
    $inspector = new GameInspector(Side::HOME, $botNumber, $snapshot);
	$randomPlayer = Player::fromLugoPlayer(array_values(array_filter([...$snapshot->getHomeTeam()->getPlayers()], fn(LugoPlayer $p) => $p->getNumber() != $botNumber))[rand(0, 9)]);

	$order = $inspector->makeOrderKickToPlayer($randomPlayer);

	expect($order)->toBeInstanceOf(Order::class);
	expect($order->getKick())->toBeInstanceOf(KickOrder::class);
	expect($order->getKick()->getVelocity()->getDirection())->toEqual($inspector->getBall()->getPosition()->directionTo($randomPlayer->getPosition())->toLugoVector());
	expect($order->getKick()->getVelocity()->getSpeed())->toEqual(SPECS::BALL_MAX_SPEED);

	$order = $inspector->makeOrderKickToPlayer($randomPlayer, 30);
	expect($order->getKick()->getVelocity()->getSpeed())->toEqual(30);
});

test('DEVE retornar uma ordem de pulo para um ponto X', function () {
	$botNumber = 11;
    $snapshot = randomLugoGameSnapshot();
    $inspector = new GameInspector(Side::HOME, $botNumber, $snapshot);

	$point = randomPoint();
	$order = $inspector->makeOrderJumpToPoint($point);

	$direction = $inspector->getMyPosition()->directionTo($point);
	$upOrDown = $direction->getY() > 0 ? new Vector2D(0, 1) : new Vector2D(0, -1);

	expect($order)->toBeInstanceOf(Order::class);
	expect($order->getJump())->toBeInstanceOf(JumpOrder::class);
	expect($order->getJump()->getVelocity()->getDirection())->toEqual($upOrDown->toLugoVector());
	expect($order->getJump()->getVelocity()->getSpeed())->toEqual(SPECS::GOALKEEPER_JUMP_MAX_SPEED);

	$order = $inspector->makeOrderJumpToPoint($point, 30);
	expect($order->getJump()->getVelocity()->getSpeed())->toEqual(30);
});

test('DEVE retornar uma ordem de movimentação para a direção atual com velocidade 0', function () {
	$botNumber = 11;
    $snapshot = randomLugoGameSnapshot();
    $inspector = new GameInspector(Side::HOME, $botNumber, $snapshot);

	$order = $inspector->makeOrderStop();

	expect($order)->toBeInstanceOf(Order::class);
	expect($order->getMove())->toBeInstanceOf(MoveOrder::class);
	expect($order->getMove()->getVelocity()->getDirection())->toEqual($inspector->getMyDirection()->toLugoVector());
	expect($order->getMove()->getVelocity()->getSpeed())->toEqual(0);
});

test('DEVE retornar uma ordem de pegar a bola', function () {
	$botNumber = 11;
    $snapshot = randomLugoGameSnapshot();
    $inspector = new GameInspector(Side::HOME, $botNumber, $snapshot);

	$order = $inspector->makeOrderCatch();

	expect($order)->toBeInstanceOf(Order::class);
	expect($order->getCatch())->toBeInstanceOf(CatchOrder::class);
});

test('DEVE retornar uma ordem de movimentação para um ponto X com velocidade 0', function () {
	$botNumber = 11;
    $snapshot = randomLugoGameSnapshot();
    $inspector = new GameInspector(Side::HOME, $botNumber, $snapshot);
	$point = randomPoint();
	$order = $inspector->makeOrderLookAtPoint($point);

	expect($order)->toBeInstanceOf(Order::class);
	expect($order->getMove())->toBeInstanceOf(MoveOrder::class);
	expect($order->getMove()->getVelocity()->getDirection())->toEqual($inspector->getMyPosition()->directionTo($point)->toLugoVector());
	expect($order->getMove()->getVelocity()->getSpeed())->toEqual(0);
});

test('DEVE retornar uma ordem de movimentação para uma direção X com velocidade 0', function () {
	$botNumber = 11;
    $snapshot = randomLugoGameSnapshot();
    $inspector = new GameInspector(Side::HOME, $botNumber, $snapshot);
	$direction = randomDirection();
	$order = $inspector->makeOrderLookAtDirection($direction);

	expect($order)->toBeInstanceOf(Order::class);
	expect($order->getMove())->toBeInstanceOf(MoveOrder::class);
	expect($order->getMove()->getVelocity()->getDirection())->toEqual($direction->toLugoVector());
	expect($order->getMove()->getVelocity()->getSpeed())->toEqual(0);
});

test('DEVE retornar corretamente o estado do jogador', function () {
    $inspector = randomGameInspectorInDefending(Side::HOME, 10);
	expect($inspector->getMyState())->toBe(PlayerState::DEFENDING);

	$inspector = randomGameInspectorInSupporting(Side::HOME, 10);
	expect($inspector->getMyState())->toBe(PlayerState::SUPPORTING);

	$inspector = randomGameInspectorInHolding(Side::HOME, 10);
	expect($inspector->getMyState())->toBe(PlayerState::HOLDING);

	$inspector = randomGameInspectorInDisputing(Side::HOME, 10);
	expect($inspector->getMyState())->toBe(PlayerState::DISPUTING);
});

test('DEVE retornar o centro do campo', function () {
    $inspector = new GameInspector(Side::HOME, 10, randomLugoGameSnapshot());
	
	expect($inspector->getFieldCenter())->toEqual(new Point(SPECS::FIELD_CENTER_X, SPECS::FIELD_CENTER_Y));
});
