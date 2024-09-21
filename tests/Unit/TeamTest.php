<?php

use Lugo4php\Team;
use Lugo4php\Side;
use Lugo\Team as LugoTeam;

test('DEVE criar uma instância de Team corretamente', function () {
    $side = Side::HOME;
    $players = [randomPlayer(), randomPlayer()];
    $team = new Team('TeamName', 3, $side, $players);

    expect($team->getName())->toBe('TeamName');
    expect($team->getScore())->toBe(3);
    expect($team->getSide())->toBe($side);
    expect($team->getPlayers())->toBe($players);
});

test('DEVE retornar o nome do time', function () {
    $team = new Team('TeamName', 0, Side::AWAY, []);
    expect($team->getName())->toBe('TeamName');
});

test('DEVE retornar a pontuação do time', function () {
    $team = new Team('TeamName', 5, Side::HOME, []);
    expect($team->getScore())->toBe(5);
});

test('DEVE retornar o lado do time', function () {
    $team = new Team('TeamName', 0, Side::AWAY, []);
    expect($team->getSide())->toBe(Side::AWAY);
});

test('DEVE retornar os jogadores do time', function () {
    $players = [randomPlayer(), randomPlayer()];
    $team = new Team('TeamName', 0, Side::HOME, $players);
    expect($team->getPlayers())->toBe($players);
});

test('DEVE criar uma instância de Team a partir de LugoTeam', function () {
    $lugoTeam = new LugoTeam();
    $lugoTeam->setName('TeamName');
    $lugoTeam->setScore(3);
    $lugoTeam->setSide(Side::HOME->value);

    $lugoPlayer1 = randomLugoPlayer();
    $lugoPlayer2 = randomLugoPlayer();

    $lugoTeam->setPlayers([$lugoPlayer1, $lugoPlayer2]);

    $team = Team::fromLugoTeam($lugoTeam);

    expect($team)->toBeInstanceOf(Team::class);
    expect($team->getName())->toBe('TeamName');
    expect($team->getScore())->toBe(3);
    expect($team->getSide())->toBe(Side::HOME);
    expect($team->getPlayers())->toHaveCount(2);
});
