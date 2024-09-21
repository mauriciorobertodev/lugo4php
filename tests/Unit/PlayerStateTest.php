<?php

use Lugo4php\PlayerState;

test('DEVE retornar a instância correta do PlayerState a partir de uma string', function () {
    expect(PlayerState::fromString('supporting'))->toBe(PlayerState::SUPPORTING);
    expect(PlayerState::fromString('holding'))->toBe(PlayerState::HOLDING);
    expect(PlayerState::fromString('defending'))->toBe(PlayerState::DEFENDING);
    expect(PlayerState::fromString('disputing'))->toBe(PlayerState::DISPUTING);
});

test('DEVE ignorar maiúsculas e minúsculas ao converter a string para PlayerState', function () {
    expect(PlayerState::fromString('SUPPORTING'))->toBe(PlayerState::SUPPORTING);
    expect(PlayerState::fromString('Holding'))->toBe(PlayerState::HOLDING);
    expect(PlayerState::fromString('dEfEnDiNg'))->toBe(PlayerState::DEFENDING);
    expect(PlayerState::fromString('DISPUTING'))->toBe(PlayerState::DISPUTING);
});

test('DEVE lançar uma exceção para um valor inválido', function () {
    PlayerState::fromString('invalid_value');
})->throws(RuntimeException::class, "Valor 'invalid_value' inválido para estado do player");
