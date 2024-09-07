<?php

require 'vendor/autoload.php';

use Example\Bot\BotTester;
use Lugo4php\Client;
use Lugo4php\Env;
use Lugo4php\Mapper;

$env = new Env();

$mapper = new Mapper(10, 6, $env->getBotSide());

$initPosition = $mapper->getRegion(1, 4)->getCenter();

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
