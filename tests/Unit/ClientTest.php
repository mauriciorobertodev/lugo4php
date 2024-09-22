<?php

use Lugo4php\Ball;
use Lugo4php\Client;
use Lugo4php\Env;
use Lugo4php\PlayerState;
use Lugo4php\SPECS;
use Lugo4php\Vector2D;
use Lugo\KickOrder;
use Lugo\Order;
use Lugo\OrderSet;
use Tests\Classes\BotTester;

beforeEach(function () {
    putenv('BOT_GRPC_URL=localhost:5000');
    putenv('BOT_GRPC_INSECURE=true');
    putenv('BOT_TEAM=home');
    putenv('BOT_NUMBER=5');
    putenv('BOT_TOKEN=my_secret_token');
});

test("DEVE retornar o OrderSet correto com a resposta do bot no estado HOLDING", function() {
	$bot = new BotTester();
	$env = new Env();
	$client = new Client(
		$env->getGrpcUrl(),
		$env->getGrpcInsecure(),
		$env->getBotToken(),
		$env->getBotSide(),
		$env->getBotNumber(),
		randomPoint()
	);

	$inspector = randomGameInspectorInHolding($env->getBotSide(), $env->getBotNumber());
	$orders = $bot->onHolding($inspector);
	$filteredOrders = array_values(array_filter($orders, fn($order) => $order instanceof Order));
	$orderSet = $client->getOrderSet($inspector, $bot);

	expect($inspector->getMyState())->toBe(PlayerState::HOLDING);
	expect($orderSet)->toBeInstanceOf(OrderSet::class);
	expect([...$orderSet->getOrders()])->not()->toEqual($orders);
	expect($orders[0])->toEqual('onHolding');
	expect([...$orderSet->getOrders()])->toHaveCount(count($filteredOrders));
	expect([...$orderSet->getOrders()])->toEqual($filteredOrders);
});

test("DEVE retornar o OrderSet correto com a resposta do bot no estado DISPUTING", function() {
	$bot = new BotTester();
	$env = new Env();
	$client = new Client(
		$env->getGrpcUrl(),
		$env->getGrpcInsecure(),
		$env->getBotToken(),
		$env->getBotSide(),
		$env->getBotNumber(),
		randomPoint()
	);

	$inspector = randomGameInspectorInDisputing($env->getBotSide(), $env->getBotNumber());
	$orders = $bot->onDisputing($inspector);
	$filteredOrders = array_values(array_filter($orders, fn($order) => $order instanceof Order));
	$orderSet = $client->getOrderSet($inspector, $bot);

	expect($inspector->getMyState())->toBe(PlayerState::DISPUTING);
	expect($orderSet)->toBeInstanceOf(OrderSet::class);
	expect([...$orderSet->getOrders()])->not()->toEqual($orders);
	expect($orders[0])->toEqual('onDisputing');
	expect([...$orderSet->getOrders()])->toHaveCount(count($filteredOrders));
	expect([...$orderSet->getOrders()])->toEqual($filteredOrders);
});

test("DEVE retornar o OrderSet correto com a resposta do bot no estado SUPPORTING", function() {
	$bot = new BotTester();
	$env = new Env();
	$client = new Client(
		$env->getGrpcUrl(),
		$env->getGrpcInsecure(),
		$env->getBotToken(),
		$env->getBotSide(),
		$env->getBotNumber(),
		randomPoint()
	);

	$inspector = randomGameInspectorInSupporting($env->getBotSide(), $env->getBotNumber());
	$orders = $bot->onSupporting($inspector);
	$filteredOrders = array_values(array_filter($orders, fn($order) => $order instanceof Order));
	$orderSet = $client->getOrderSet($inspector, $bot);

	expect($inspector->getMyState())->toBe(PlayerState::SUPPORTING);
	expect($orderSet)->toBeInstanceOf(OrderSet::class);
	expect([...$orderSet->getOrders()])->not()->toEqual($orders);
	expect($orders[0])->toEqual('onSupporting');
	expect([...$orderSet->getOrders()])->toHaveCount(count($filteredOrders));
	expect([...$orderSet->getOrders()])->toEqual($filteredOrders);
});

test("DEVE retornar o OrderSet correto com a resposta do bot no estado DEFENDING", function() {
	$bot = new BotTester();
	$env = new Env();
	$client = new Client(
		$env->getGrpcUrl(),
		$env->getGrpcInsecure(),
		$env->getBotToken(),
		$env->getBotSide(),
		$env->getBotNumber(),
		randomPoint()
	);

	$inspector = randomGameInspectorInDefending($env->getBotSide(), $env->getBotNumber());
	$orders = $bot->onDefending($inspector);
	$filteredOrders = array_values(array_filter($orders, fn($order) => $order instanceof Order));
	$orderSet = $client->getOrderSet($inspector, $bot);

	expect($inspector->getMyState())->toBe(PlayerState::DEFENDING);
	expect($orderSet)->toBeInstanceOf(OrderSet::class);
	expect([...$orderSet->getOrders()])->not()->toEqual($orders);
	expect($orders[0])->toEqual('onDefending');
	expect([...$orderSet->getOrders()])->toHaveCount(count($filteredOrders));
	expect([...$orderSet->getOrders()])->toEqual($filteredOrders);
});

test("DEVE retornar o OrderSet correto com a resposta do bot sendo o GOLEIRO", function() {
	putenv(sprintf('BOT_NUMBER=%s', SPECS::GOALKEEPER_NUMBER));

	$bot = new BotTester();
	$env = new Env();
	$client = new Client(
		$env->getGrpcUrl(),
		$env->getGrpcInsecure(),
		$env->getBotToken(),
		$env->getBotSide(),
		$env->getBotNumber(),
		randomPoint()
	);

	$inspector = randomGameInspectorInDefending($env->getBotSide(), $env->getBotNumber());
	$orders = $bot->asGoalkeeper($inspector, $inspector->getMyState());
	$filteredOrders = array_values(array_filter($orders, fn($order) => $order instanceof Order));
	$orderSet = $client->getOrderSet($inspector, $bot);

	expect($inspector->getMe()->isGoalkeeper())->toBeTrue();
	expect($inspector->getMyState())->toBe(PlayerState::DEFENDING);
	expect($orderSet)->toBeInstanceOf(OrderSet::class);
	expect([...$orderSet->getOrders()])->not()->toEqual($orders);
	expect($orders[0])->toEqual('asGoalkeeper');
	expect([...$orderSet->getOrders()])->toHaveCount(count($filteredOrders));
	expect([...$orderSet->getOrders()])->toEqual($filteredOrders);
});