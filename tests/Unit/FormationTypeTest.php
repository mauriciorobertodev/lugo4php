<?php

use Lugo4php\FormationType;

test('DEVE retornar REGIONS quando o valor for regions', function () {
    $result = FormationType::fromString('regions');
    expect($result)->toBe(FormationType::REGIONS);
});

test('DEVE retornar POINTS quando o valor for points', function () {
    $result = FormationType::fromString('points');
    expect($result)->toBe(FormationType::POINTS);
});

test('DEVE retornar NOT_DEFINED quando o valor for vazio', function () {
    $result = FormationType::fromString('');
    expect($result)->toBe(FormationType::NOT_DEFINED);
});

test('DEVE lançar uma exceção quando o valor for inválido', function () {
    FormationType::fromString('invalid');
})->throws(RuntimeException::class, "Valor 'invalid' inválido para um tipo de formação");
