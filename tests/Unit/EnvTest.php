<?php

use Lugo4php\Env;
use Lugo4php\Side;
use Lugo4php\SPECS;

beforeEach(function () {
    putenv('BOT_GRPC_URL=localhost:5000');
    putenv('BOT_GRPC_INSECURE=true');
    putenv('BOT_TEAM=home');
    putenv('BOT_NUMBER=5');
    putenv('BOT_TOKEN=my_secret_token');
});

afterEach(function () {
    putenv('BOT_GRPC_URL');
    putenv('BOT_GRPC_INSECURE');
    putenv('BOT_TEAM');
    putenv('BOT_NUMBER');
    putenv('BOT_TOKEN');
});

test('DEVE ser construído com oss valores corretos', function () {
    $env = new Env();

    expect($env->getGrpcUrl())->toBe('localhost:5000');
    expect($env->getGrpcInsecure())->toBeTrue();
    expect($env->getBotSide())->toBe(Side::HOME);
    expect($env->getBotNumber())->toBe(5);
    expect($env->getBotToken())->toBe('my_secret_token');
});

test('DEVE lançar um erro com um número de bot inválido', function () {
    putenv('BOT_NUMBER=999'); // Define um número inválido fora do intervalo permitido
	expect(fn() => new Env())->toThrow(\InvalidArgumentException::class, "Número do bot inválido, '999', deve estar entre 1 e " . SPECS::MAX_PLAYERS);
});

test('DEVE definir as propriedades padrões e erro nas que não existem um padrão', function () {
    putenv('BOT_GRPC_URL=');
    putenv('BOT_GRPC_INSECURE=');

    $env = new Env();
    expect($env->getGrpcUrl())->toBe('localhost:5000');
    expect($env->getGrpcInsecure())->toBeTrue();

    putenv('BOT_TEAM=');
    expect(fn() => new Env())->toThrow(InvalidArgumentException::class, sprintf("Valor inválido para o lado do time: '%s'", ''));
    putenv('BOT_TEAM=invalid');
    expect(fn() => new Env())->toThrow(InvalidArgumentException::class, sprintf("Valor inválido para o lado do time: '%s'", 'invalid'));
    putenv('BOT_TEAM=home');

    putenv('BOT_NUMBER=');
    expect(fn() => new Env())->toThrow(InvalidArgumentException::class, sprintf("Número do bot inválido, '%s', deve estar entre 1 e %s", '0', SPECS::MAX_PLAYERS));
  
    putenv('BOT_NUMBER=12');
    expect(fn() => new Env())->toThrow(InvalidArgumentException::class, sprintf("Número do bot inválido, '%s', deve estar entre 1 e %s", '12', SPECS::MAX_PLAYERS));
    putenv('BOT_NUMBER=10');

    putenv('BOT_GRPC_INSECURE=false');
    putenv('BOT_TOKEN=');
    expect(fn() => new Env())->toThrow(InvalidArgumentException::class, "Partida no modo seguro é necessário definir um token válido");
});
