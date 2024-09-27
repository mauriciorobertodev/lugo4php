<?php

use Lugo4php\GameInspector;
use Lugo4php\Player;
use Lugo4php\PlayerState;
use Lugo4php\Side;

test('DEVE gerar um GameInspector aleatório em um estado de HOLDING', function() {
	for ($i=0; $i < 50; $i++) { 
		$inspector = randomGameInspectorInHolding(Side::HOME, 10);

		expect($inspector)->toBeInstanceOf(GameInspector::class);
		expect($inspector->getMe())->toBeInstanceOf(Player::class);
		expect($inspector->getMyNumber())->toBe(10);
		expect($inspector->getMyTeamSide())->toBe(Side::HOME);
		expect($inspector->getMyState())->toBe(PlayerState::HOLDING);
	}
	
	for ($i=0; $i < 50; $i++) { 
		$inspector = randomGameInspectorInHolding(Side::AWAY, 10);

		expect($inspector)->toBeInstanceOf(GameInspector::class);
		expect($inspector->getMe())->toBeInstanceOf(Player::class);
		expect($inspector->getMyNumber())->toBe(10);
		expect($inspector->getMyTeamSide())->toBe(Side::AWAY);
		expect($inspector->getMyState())->toBe(PlayerState::HOLDING);
	}
});

test('DEVE gerar um GameInspector aleatório em um estado de DISPUTING', function() {
	for ($i=0; $i < 50; $i++) { 
		$inspector = randomGameInspectorInDisputing(Side::HOME, 10);

		expect($inspector)->toBeInstanceOf(GameInspector::class);
		expect($inspector->getMe())->toBeInstanceOf(Player::class);
		expect($inspector->getMyNumber())->toBe(10);
		expect($inspector->getMyTeamSide())->toBe(Side::HOME);
		expect($inspector->getMyState())->toBe(PlayerState::DISPUTING);
	}
	
	for ($i=0; $i < 50; $i++) { 
		$inspector = randomGameInspectorInDisputing(Side::AWAY, 10);

		expect($inspector)->toBeInstanceOf(GameInspector::class);
		expect($inspector->getMe())->toBeInstanceOf(Player::class);
		expect($inspector->getMyNumber())->toBe(10);
		expect($inspector->getMyTeamSide())->toBe(Side::AWAY);
		expect($inspector->getMyState())->toBe(PlayerState::DISPUTING);
	}
});

test('DEVE gerar um GameInspector aleatório em um estado de SUPPORTING', function() {
	for ($i=0; $i < 50; $i++) { 
		$inspector = randomGameInspectorInSupporting(Side::HOME, 10);

		expect($inspector)->toBeInstanceOf(GameInspector::class);
		expect($inspector->getMe())->toBeInstanceOf(Player::class);
		expect($inspector->getMyNumber())->toBe(10);
		expect($inspector->getMyTeamSide())->toBe(Side::HOME);
		expect($inspector->getMyState())->toBe(PlayerState::SUPPORTING);
	}
	
	for ($i=0; $i < 50; $i++) { 
		$inspector = randomGameInspectorInSupporting(Side::AWAY, 10);

		expect($inspector)->toBeInstanceOf(GameInspector::class);
		expect($inspector->getMe())->toBeInstanceOf(Player::class);
		expect($inspector->getMyNumber())->toBe(10);
		expect($inspector->getMyTeamSide())->toBe(Side::AWAY);
		expect($inspector->getMyState())->toBe(PlayerState::SUPPORTING);
	}
});

test('DEVE gerar um GameInspector aleatório em um estado de DEFENDING', function() {
	for ($i=0; $i < 50; $i++) { 
		$inspector = randomGameInspectorInDefending(Side::HOME, 10);

		expect($inspector)->toBeInstanceOf(GameInspector::class);
		expect($inspector->getMe())->toBeInstanceOf(Player::class);
		expect($inspector->getMyNumber())->toBe(10);
		expect($inspector->getMyTeamSide())->toBe(Side::HOME);
		expect($inspector->getMyState())->toBe(PlayerState::DEFENDING);
	}
	
	for ($i=0; $i < 50; $i++) { 
		$inspector = randomGameInspectorInDefending(Side::AWAY, 10);

		expect($inspector)->toBeInstanceOf(GameInspector::class);
		expect($inspector->getMe())->toBeInstanceOf(Player::class);
		expect($inspector->getMyNumber())->toBe(10);
		expect($inspector->getMyTeamSide())->toBe(Side::AWAY);
		expect($inspector->getMyState())->toBe(PlayerState::DEFENDING);
	}
});

