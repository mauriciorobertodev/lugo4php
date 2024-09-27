<?php

use Lugo4php\Ball;
use Lugo4php\GameInspector;
use Lugo4php\Goal;
use Lugo4php\Mapper;
use Lugo4php\Player;
use Lugo4php\PlayerState;
use Lugo4php\Point;
use Lugo4php\Region;
use Lugo4php\ShotClock;
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
    expect($inspector->getMyTeamSide())->toEqual($botSide);
    expect($inspector->getMyTeam())->toEqual(Team::fromLugoTeam($snapshot->getHomeTeam()));
    expect($inspector->getMyGoalkeeper()->getNumber())->toEqual(SPECS::GOALKEEPER_NUMBER);
    expect($inspector->getMyGoalkeeper()->getTeamSide())->toEqual($botSide);
    expect($inspector->tryGetMyGoalkeeper()->getTeamSide())->toEqual($botSide);
    expect($inspector->getMyPosition())->toEqual($lugo4phpMe->getPosition());
    expect($inspector->getMyDirection())->toEqual($lugo4phpMe->getDirection());
    expect($inspector->getMySpeed())->toEqual($lugo4phpMe->getSpeed());
    expect($inspector->getMyVelocity())->toEqual(Velocity::fromLugoVelocity($lugoMe->getVelocity()));
    expect($inspector->getMyPlayers())->toEqual(Team::fromLugoTeam($snapshot->getHomeTeam())->getPlayers());
    expect($inspector->getMyScore())->toEqual($snapshot->getHomeTeam()->getScore());
    expect($inspector->getMyPlayer(10))->toEqual($lugo4phpPlayer10);
    expect($inspector->tryGetMyPlayer(10))->toEqual($lugo4phpPlayer10);

	expect($inspector->getOpponentGoalkeeper())->toEqual($lugo4phpOpponent01);
	expect($inspector->tryGetOpponentGoalkeeper())->toEqual($lugo4phpOpponent01);
	expect($inspector->getOpponentPlayer(10))->toEqual($lugo4phpOpponent10);
	expect($inspector->tryGetOpponentPlayer(10))->toEqual($lugo4phpOpponent10);
	expect($inspector->getOpponentPlayers())->toEqual(Team::fromLugoTeam($snapshot->getAwayTeam())->getPlayers());
	expect($inspector->getOpponentScore())->toEqual($snapshot->getAwayTeam()->getScore());
	expect($inspector->getOpponentSide())->toEqual(Side::AWAY);
	expect($inspector->getOpponentTeam())->toEqual(Team::fromLugoTeam($snapshot->getAwayTeam()));

	expect($inspector->getTeam(Side::HOME))->toEqual(Team::fromLugoTeam($snapshot->getHomeTeam()));
	expect($inspector->getTeam(Side::AWAY))->toEqual(Team::fromLugoTeam($snapshot->getAwayTeam()));
	
	expect($inspector->getAttackGoal())->toEqual(Goal::AWAY());
	expect($inspector->getDefenseGoal())->toEqual(Goal::HOME());

	expect($inspector->getBall())->toEqual(Ball::fromLugoBall($snapshot->getBall()));
	expect($inspector->getBallPosition())->toEqual(Point::fromLugoPoint($snapshot->getBall()->getPosition()));
	expect($inspector->getBallDirection())->toEqual(Vector2D::fromLugoVector($snapshot->getBall()->getVelocity()->getDirection())->normalize());
	expect($inspector->getBallSpeed())->toEqual($snapshot->getBall()->getVelocity()->getSpeed());
	expect($inspector->getBallHasHolder())->toEqual((bool) $snapshot->getBall()->getHolder());
	expect($inspector->getBallTurnsInGoalZone())->toEqual($snapshot->getTurnsBallInGoalZone());
	expect($inspector->getBallRemainingTurnsInGoalZone())->toEqual(SPECS::BALL_TIME_IN_GOAL_ZONE - $snapshot->getTurnsBallInGoalZone());

	expect($inspector->hasShotClock())->toEqual((bool) $snapshot->getShotClock());
	
	$inspector = randomGameInspectorInHolding(Side::HOME, 7);
	$snapshot = $inspector->getSnapshot();
	expect($inspector->getShotClock())->toEqual(ShotClock::fromLugoShotClock($snapshot->getShotClock()));
});

test('DEVE retornar null quando usa o try', function () {
    $snapshot = randomLugoGameSnapshot();
	$botSide = Side::HOME;
	$botNumber = 11;
	$inspector = new GameInspector($botSide, $botNumber, $snapshot);
	$snapshot->getHomeTeam()->setPlayers([]);
	$snapshot->getAwayTeam()->setPlayers([]);

    expect($inspector->tryGetMyGoalkeeper())->toBeNull();
    expect($inspector->tryGetMyPlayer(10))->toBeNull();
    expect($inspector->tryGetOpponentGoalkeeper())->toBeNull();
    expect($inspector->tryGetOpponentPlayer(10))->toBeNull();
    expect($inspector->tryGetPlayer(Side::HOME, 10))->toBeNull();
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

	expect(fn() => $inspector->makeOrderMoveToPoint($inspector->getMyPosition()))->toThrow(RuntimeException::class);
});

test('DEVE retornar uma ordem de chute para um ponto X', function () {
	$botNumber = 11;
    $snapshot = randomLugoGameSnapshot();
    $inspector = new GameInspector(Side::HOME, $botNumber, $snapshot);
	$lugoMe = array_values(array_filter([...$snapshot->getHomeTeam()->getPlayers()], fn(LugoPlayer $p) => $p->getNumber() === $botNumber))[0];

	$point = randomPoint();
	$order = $inspector->makeOrderKickToPoint($point);

	expect($order)->toBeInstanceOf(Order::class);
	expect($order->getKick())->toBeInstanceOf(KickOrder::class);
	expect($order->getKick()->getVelocity()->getDirection())->toEqual($inspector->getBall()->getPosition()->directionTo($point)->toLugoVector());
	expect($order->getKick()->getVelocity()->getSpeed())->toEqual(SPECS::BALL_MAX_SPEED);

	$order = $inspector->makeOrderKickToPoint($point, 30);
	expect($order->getKick()->getVelocity()->getSpeed())->toEqual(30);

	expect(fn() => $inspector->makeOrderKickToPoint($inspector->getBallPosition()))->toThrow(RuntimeException::class);
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

	expect(fn() => $inspector->makeOrderMoveToDirection(new Vector2D(0,0)))->toThrow(RuntimeException::class);
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

	expect(fn() => $inspector->makeOrderKickToDirection(new Vector2D(0,0)))->toThrow(RuntimeException::class);
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

	$region = new Region(0,0, Side::HOME, $inspector->getMyPosition(), $mapper);
	expect(fn() => $inspector->makeOrderMoveToRegion($region))->toThrow(RuntimeException::class);
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
	expect($order->getKick()->getVelocity()->getDirection())->toEqual($inspector->getBallPosition()->directionTo($region->getCenter())->toLugoVector());
	expect($order->getKick()->getVelocity()->getSpeed())->toEqual(SPECS::BALL_MAX_SPEED);

	$order = $inspector->makeOrderKickToRegion($region, 30);
	expect($order->getKick()->getVelocity()->getSpeed())->toEqual(30);

	$region = new Region(0,0, Side::HOME, $inspector->getBallPosition(), $mapper);
	expect(fn() => $inspector->makeOrderKickToRegion($region))->toThrow(RuntimeException::class);
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

	$randomPlayer = randomPlayer($inspector->getMyPosition());
	expect(fn() => $inspector->makeOrderMoveToPlayer($randomPlayer))->toThrow(RuntimeException::class);
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

	$randomPlayer = randomPlayer($inspector->getBallPosition());
	expect(fn() => $inspector->makeOrderKickToPlayer($randomPlayer))->toThrow(RuntimeException::class);
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

	expect(fn() => $inspector->makeOrderJumpToPoint($inspector->getMyPosition()))->toThrow(RuntimeException::class);

	$order = $inspector->makeOrderJumpToPoint($point, 30);
	expect($order->getJump()->getVelocity()->getSpeed())->toEqual(30);
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

	expect(fn() => $inspector->makeOrderLookAtPoint($inspector->getMyPosition()))->toThrow(RuntimeException::class);
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

	expect(fn() => $inspector->makeOrderLookAtDirection(new Vector2D(0,0)))->toThrow(RuntimeException::class);
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

test('DEVE retornar uma ordem de pegar a bola', function () {
	$botNumber = 11;
    $snapshot = randomLugoGameSnapshot();
    $inspector = new GameInspector(Side::HOME, $botNumber, $snapshot);

	$order = $inspector->makeOrderCatch();

	expect($order)->toBeInstanceOf(Order::class);
	expect($order->getCatch())->toBeInstanceOf(CatchOrder::class);
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

// ##########################
test('DEVE retornar uma ordem de movimentação para um ponto X ou null', function () {
	$botNumber = 11;
    $snapshot = randomLugoGameSnapshot();
    $inspector = new GameInspector(Side::HOME, $botNumber, $snapshot);
	$lugoMe = array_values(array_filter([...$snapshot->getHomeTeam()->getPlayers()], fn(LugoPlayer $p) => $p->getNumber() === $botNumber))[0];
	$mePosition = Point::fromLugoPoint($lugoMe->getPosition());

	$point = randomPoint();
	$order = $inspector->tryMakeOrderMoveToPoint($point);

	expect($order)->toBeInstanceOf(Order::class);
	expect($order->getMove())->toBeInstanceOf(MoveOrder::class);
	expect($order->getMove()->getVelocity()->getDirection())->toEqual($mePosition->directionTo($point)->toLugoVector());
	expect($order->getMove()->getVelocity()->getSpeed())->toEqual(SPECS::PLAYER_MAX_SPEED);

	$order = $inspector->tryMakeOrderMoveToPoint($point, 30);
	expect($order->getMove()->getVelocity()->getSpeed())->toEqual(30);

	expect($inspector->tryMakeOrderMoveToPoint($inspector->getMyPosition()))->toBeNull();
});

test('DEVE retornar uma ordem de chute para um ponto X ou null', function () {
	$botNumber = 11;
    $snapshot = randomLugoGameSnapshot();
    $inspector = new GameInspector(Side::HOME, $botNumber, $snapshot);
	$lugoMe = array_values(array_filter([...$snapshot->getHomeTeam()->getPlayers()], fn(LugoPlayer $p) => $p->getNumber() === $botNumber))[0];

	$point = randomPoint();
	$order = $inspector->tryMakeOrderKickToPoint($point);

	expect($order)->toBeInstanceOf(Order::class);
	expect($order->getKick())->toBeInstanceOf(KickOrder::class);
	expect($order->getKick()->getVelocity()->getDirection())->toEqual($inspector->getBall()->getPosition()->directionTo($point)->toLugoVector());
	expect($order->getKick()->getVelocity()->getSpeed())->toEqual(SPECS::BALL_MAX_SPEED);

	$order = $inspector->tryMakeOrderKickToPoint($point, 30);
	expect($order->getKick()->getVelocity()->getSpeed())->toEqual(30);

	expect($inspector->tryMakeOrderKickToPoint($inspector->getBallPosition()))->toBeNull();
});

test('DEVE retornar uma ordem de movimentação para uma direção X ou null', function () {
	$botNumber = 11;
    $snapshot = randomLugoGameSnapshot();
    $inspector = new GameInspector(Side::HOME, $botNumber, $snapshot);

	$direction = randomDirection();
	$order = $inspector->tryMakeOrderMoveToDirection($direction);

	expect($order)->toBeInstanceOf(Order::class);
	expect($order->getMove())->toBeInstanceOf(MoveOrder::class);
	expect($order->getMove()->getVelocity()->getDirection())->toEqual($direction->toLugoVector());
	expect($order->getMove()->getVelocity()->getSpeed())->toEqual(SPECS::PLAYER_MAX_SPEED);

	$order = $inspector->tryMakeOrderMoveToDirection($direction, 30);
	expect($order->getMove()->getVelocity()->getSpeed())->toEqual(30);

	expect($inspector->tryMakeOrderMoveToDirection(new Vector2D(0,0)))->toBeNull();
});

test('DEVE retornar uma ordem de chute para uma direção X ou null', function () {
	$botNumber = 11;
    $snapshot = randomLugoGameSnapshot();
    $inspector = new GameInspector(Side::HOME, $botNumber, $snapshot);

	$direction = randomDirection();
	$order = $inspector->tryMakeOrderKickToDirection($direction);

	expect($order)->toBeInstanceOf(Order::class);
	expect($order->getKick())->toBeInstanceOf(KickOrder::class);
	expect($order->getKick()->getVelocity()->getDirection())->toEqual($direction->toLugoVector());
	expect($order->getKick()->getVelocity()->getSpeed())->toEqual(SPECS::BALL_MAX_SPEED);

	$order = $inspector->tryMakeOrderKickToDirection($direction, 30);
	expect($order->getKick()->getVelocity()->getSpeed())->toEqual(30);

	expect($inspector->tryMakeOrderKickToDirection(new Vector2D(0,0)))->toBeNull();
});

test('DEVE retornar uma ordem de movimentação para uma região X ou null', function () {
	$botNumber = 11;
    $snapshot = randomLugoGameSnapshot();
    $inspector = new GameInspector(Side::HOME, $botNumber, $snapshot);
	$lugoMe = array_values(array_filter([...$snapshot->getHomeTeam()->getPlayers()], fn(LugoPlayer $p) => $p->getNumber() === $botNumber))[0];
	$mePosition = Point::fromLugoPoint($lugoMe->getPosition());
	$mapper = new Mapper(10, 10, Side::HOME);
	$region = $mapper->getRandomRegion();

	$order = $inspector->tryMakeOrderMoveToRegion($region);

	expect($order)->toBeInstanceOf(Order::class);
	expect($order->getMove())->toBeInstanceOf(MoveOrder::class);
	expect($order->getMove()->getVelocity()->getDirection())->toEqual($mePosition->directionTo($region->getCenter())->toLugoVector());
	expect($order->getMove()->getVelocity()->getSpeed())->toEqual(SPECS::PLAYER_MAX_SPEED);

	$order = $inspector->tryMakeOrderMoveToRegion($region, 30);
	expect($order->getMove()->getVelocity()->getSpeed())->toEqual(30);

	$region = new Region(0,0, Side::HOME, $inspector->getMyPosition(), $mapper);
	expect($inspector->tryMakeOrderMoveToRegion($region))->toBeNull();
});

test('DEVE retornar uma ordem de chute para uma região X ou null', function () {
	$botNumber = 11;
    $snapshot = randomLugoGameSnapshot();
    $inspector = new GameInspector(Side::HOME, $botNumber, $snapshot);
	$lugoMe = array_values(array_filter([...$snapshot->getHomeTeam()->getPlayers()], fn(LugoPlayer $p) => $p->getNumber() === $botNumber))[0];
	$mePosition = Point::fromLugoPoint($lugoMe->getPosition());
	$mapper = new Mapper(10, 10, Side::HOME);
	$region = $mapper->getRandomRegion();

	$order = $inspector->tryMakeOrderKickToRegion($region);

	expect($order)->toBeInstanceOf(Order::class);
	expect($order->getKick())->toBeInstanceOf(KickOrder::class);
	expect($order->getKick()->getVelocity()->getDirection())->toEqual($inspector->getBallPosition()->directionTo($region->getCenter())->toLugoVector());
	expect($order->getKick()->getVelocity()->getSpeed())->toEqual(SPECS::BALL_MAX_SPEED);

	$order = $inspector->tryMakeOrderKickToRegion($region, 30);
	expect($order->getKick()->getVelocity()->getSpeed())->toEqual(30);

	$region = new Region(0,0, Side::HOME, $inspector->getBallPosition(), $mapper);
	expect($inspector->tryMakeOrderKickToRegion($region))->toBeNull();
});

test('DEVE retornar uma ordem de movimentação para um player X ou null', function () {
	$botNumber = 11;
    $snapshot = randomLugoGameSnapshot();
    $inspector = new GameInspector(Side::HOME, $botNumber, $snapshot);
	$lugoMe = array_values(array_filter([...$snapshot->getHomeTeam()->getPlayers()], fn(LugoPlayer $p) => $p->getNumber() === $botNumber))[0];
	$mePosition = Point::fromLugoPoint($lugoMe->getPosition());
	$randomPlayer = Player::fromLugoPlayer(array_values(array_filter([...$snapshot->getHomeTeam()->getPlayers()], fn(LugoPlayer $p) => $p->getNumber() != $botNumber))[rand(0, 9)]);

	$order = $inspector->tryMakeOrderMoveToPlayer($randomPlayer);

	expect($order)->toBeInstanceOf(Order::class);
	expect($order->getMove())->toBeInstanceOf(MoveOrder::class);
	expect($order->getMove()->getVelocity()->getDirection())->toEqual($mePosition->directionTo($randomPlayer->getPosition())->toLugoVector());
	expect($order->getMove()->getVelocity()->getSpeed())->toEqual(SPECS::PLAYER_MAX_SPEED);

	$order = $inspector->tryMakeOrderMoveToPlayer($randomPlayer, 30);
	expect($order->getMove()->getVelocity()->getSpeed())->toEqual(30);

	$randomPlayer = randomPlayer($inspector->getMyPosition());
	expect($inspector->tryMakeOrderMoveToPlayer($randomPlayer))->toBeNull();
});

test('DEVE retornar uma ordem de chute para um player X ou null', function () {
	$botNumber = 11;
    $snapshot = randomLugoGameSnapshot();
    $inspector = new GameInspector(Side::HOME, $botNumber, $snapshot);
	$randomPlayer = Player::fromLugoPlayer(array_values(array_filter([...$snapshot->getHomeTeam()->getPlayers()], fn(LugoPlayer $p) => $p->getNumber() != $botNumber))[rand(0, 9)]);

	$order = $inspector->tryMakeOrderKickToPlayer($randomPlayer);

	expect($order)->toBeInstanceOf(Order::class);
	expect($order->getKick())->toBeInstanceOf(KickOrder::class);
	expect($order->getKick()->getVelocity()->getDirection())->toEqual($inspector->getBall()->getPosition()->directionTo($randomPlayer->getPosition())->toLugoVector());
	expect($order->getKick()->getVelocity()->getSpeed())->toEqual(SPECS::BALL_MAX_SPEED);
	
	$order = $inspector->tryMakeOrderKickToPlayer($randomPlayer, 30);
	expect($order->getKick()->getVelocity()->getSpeed())->toEqual(30);

	$randomPlayer = randomPlayer($inspector->getBallPosition());
	expect($inspector->tryMakeOrderKickToPlayer($randomPlayer))->toBeNull();
});

test('DEVE retornar uma ordem de pulo para um ponto X ou null', function () {
	$botNumber = 11;
    $snapshot = randomLugoGameSnapshot();
    $inspector = new GameInspector(Side::HOME, $botNumber, $snapshot);

	$point = randomPoint();
	$order = $inspector->tryMakeOrderJumpToPoint($point);

	$direction = $inspector->getMyPosition()->directionTo($point);
	$upOrDown = $direction->getY() > 0 ? new Vector2D(0, 1) : new Vector2D(0, -1);

	expect($order)->toBeInstanceOf(Order::class);
	expect($order->getJump())->toBeInstanceOf(JumpOrder::class);
	expect($order->getJump()->getVelocity()->getDirection())->toEqual($upOrDown->toLugoVector());
	expect($order->getJump()->getVelocity()->getSpeed())->toEqual(SPECS::GOALKEEPER_JUMP_MAX_SPEED);

	expect($inspector->tryMakeOrderJumpToPoint($inspector->getMyPosition()))->toBeNull();

	$order = $inspector->tryMakeOrderJumpToPoint($point, 30);
	expect($order->getJump()->getVelocity()->getSpeed())->toEqual(30);
});

test('DEVE retornar uma ordem de movimentação para um ponto X com velocidade 0 ou null', function () {
	$botNumber = 11;
    $snapshot = randomLugoGameSnapshot();
    $inspector = new GameInspector(Side::HOME, $botNumber, $snapshot);
	$point = randomPoint();
	$order = $inspector->tryMakeOrderLookAtPoint($point);

	expect($order)->toBeInstanceOf(Order::class);
	expect($order->getMove())->toBeInstanceOf(MoveOrder::class);
	expect($order->getMove()->getVelocity()->getDirection())->toEqual($inspector->getMyPosition()->directionTo($point)->toLugoVector());
	expect($order->getMove()->getVelocity()->getSpeed())->toEqual(0);

	expect($inspector->tryMakeOrderLookAtPoint($inspector->getMyPosition()))->toBeNull();
});

test('DEVE retornar uma ordem de movimentação para uma direção X com velocidade 0 ou null', function () {
	$botNumber = 11;
    $snapshot = randomLugoGameSnapshot();
    $inspector = new GameInspector(Side::HOME, $botNumber, $snapshot);
	$direction = randomDirection();
	$order = $inspector->tryMakeOrderLookAtDirection($direction);

	expect($order)->toBeInstanceOf(Order::class);
	expect($order->getMove())->toBeInstanceOf(MoveOrder::class);
	expect($order->getMove()->getVelocity()->getDirection())->toEqual($direction->toLugoVector());
	expect($order->getMove()->getVelocity()->getSpeed())->toEqual(0);

	expect($inspector->tryMakeOrderLookAtDirection(new Vector2D(0,0)))->toBeNull();
});