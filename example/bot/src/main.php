<?php

require 'example/bot/vendor/autoload.php';
// require 'vendor/autoload.php';
require 'settings.php';

use Example\Bot\BotTester;
use Lugo4php\Client;
use Lugo4php\Env;
use Lugo4php\Mapper;

$env = new Env();

$mapper = new Mapper(MAPPER_COLS, MAPPER_ROWS, $env->getBotSide());
$initRegion = PLAYER_INITIAL_POSITIONS[$env->getBotNumber()];
$initPosition = $mapper->getRegion($initRegion['col'], $initRegion['row'])->getCenter();

$bot = new BotTester($mapper);

$client = new Client(
	$env->getGrpcUrl(),
	$env->getGrpcInsecure(),
	$env->getBotToken(),
	$env->getBotSide(),
	$env->getBotNumber(),
	$initPosition
);

$client->playAsBot($bot);