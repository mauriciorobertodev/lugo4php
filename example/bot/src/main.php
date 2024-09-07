<?php

require 'example/bot/vendor/autoload.php';
require 'settings.php';

use Example\Bot\BotTester;
use Lugo4php\Client;
use Lugo4php\Env;
use Lugo4php\Formation;
use Lugo4php\Mapper;

$env = new Env();

$mapper = new Mapper(MAPPER_COLS, MAPPER_ROWS, $env->getBotSide());

$formation = Formation::createFromArray(PLAYER_INITIAL_POSITIONS);

$initRegion = $formation->getPositionOf($env->getBotNumber());

$initPosition = $mapper->getRegion($initRegion->getX(), $initRegion->getY())->getCenter();

$bot = new BotTester(
	$env->getBotNumber(),
	$env->getBotSide(),
	$initPosition,
	$mapper
);

$client = new Client(
	$env->getGrpcUrl(),
	$env->getGrpcInsecure(),
	$env->getBotToken(),
	$env->getBotSide(),
	$env->getBotNumber(),
	$initPosition
);

$client->playAsBot($bot);
