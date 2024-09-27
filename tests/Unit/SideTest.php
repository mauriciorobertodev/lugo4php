<?php

use Lugo4php\Side;

test('DEVE converter a string "home" para Side::HOME', function () {
    $side = Side::fromString('home');
    expect($side)->toBe(Side::HOME);
});

test('DEVE converter a string "away" para Side::AWAY', function () {
    $side = Side::fromString('away');
    expect($side)->toBe(Side::AWAY);
});

test('DEVE lançar exceção para uma string inválida no método fromString', function () {
    expect(fn() => Side::fromString('invalid'))->toThrow(\InvalidArgumentException::class, "Valor inválido para o lado do time: 'invalid'");
});

test('DEVE converter Side::HOME para a string "home"', function () {
    $side = Side::HOME;
    expect($side->toString())->toBe('home');
});

test('DEVE converter Side::AWAY para a string "away"', function () {
    $side = Side::AWAY;
    expect($side->toString())->toBe('away');
});

test('DEVE converter o int 0 para Side::HOME', function () {
    $side = Side::fromInt(0);
    expect($side)->toBe(Side::HOME);
});

test('DEVE converter o int 1 para Side::AWAY', function () {
    $side = Side::fromInt(1);
    expect($side)->toBe(Side::AWAY);
});

test('DEVE lançar exceção para um int inválido no método fromInt', function () {
    expect(fn() => Side::fromInt(50))->toThrow(\InvalidArgumentException::class, "Valor inválido para o lado do time: '50'");
});
