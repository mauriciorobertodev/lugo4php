<?php

use Lugo4php\Formation;
use Lugo4php\Point;
use Lugo4php\FormationType;

test('DEVE construir com os dado default', function () {
    $formation = new Formation([]);

    expect($formation->getName())->toBeString();
    expect($formation->getType())->toBe(FormationType::NOT_DEFINED);
});

test('DEVE definir e pegar as propriedades corretamente', function () {
    $players = [
        1 => new Point(1, 1),
        2 => new Point(2, 2),
        3 => new Point(3, 3),
        4 => new Point(4, 4),
        5 => new Point(5, 5),
        6 => new Point(6, 6),
        7 => new Point(7, 7),
        8 => new Point(8, 8),
        9 => new Point(9, 9),
        10 => new Point(0, 0),
        11 => new Point(11, 11),
    ];
    $formation = new Formation($players);
    $formation->setName('Test Formation');
    $formation->setType(FormationType::REGIONS);

    $formation->setPositionOf(10, new Point(5, 7));
    expect($formation->getPositionOf(10))->toEqual(new Point(5, 7));
    $formation->setPositionOf(10, new Point(10, 10));

    expect($formation->getName())->toBe('Test Formation');
    expect($formation->getType())->toBe(FormationType::REGIONS);
    
    expect($formation->getPositionOf01())->toEqual(new Point(1, 1));
    $formation->definePositionOf01(1, 1);
    expect($formation->getPositionOf01())->toEqual(new Point(1, 1));
    $formation->setPositionOf01(new Point(2, 2));
    expect($formation->getPositionOf01())->toEqual(new Point(2, 2));
    
    expect($formation->getPositionOf02())->toEqual(new Point(2, 2));
    $formation->definePositionOf02(1, 1);
    expect($formation->getPositionOf02())->toEqual(new Point(1, 1));
    $formation->setPositionOf02(new Point(2, 2));
    expect($formation->getPositionOf02())->toEqual(new Point(2, 2));
    
    expect($formation->getPositionOf03())->toEqual(new Point(3, 3));
    $formation->definePositionOf03(1, 1);
    expect($formation->getPositionOf03())->toEqual(new Point(1, 1));
    $formation->setPositionOf03(new Point(2, 2));
    expect($formation->getPositionOf03())->toEqual(new Point(2, 2));
    
    expect($formation->getPositionOf04())->toEqual(new Point(4, 4));
    $formation->definePositionOf04(1, 1);
    expect($formation->getPositionOf04())->toEqual(new Point(1, 1));
    $formation->setPositionOf04(new Point(2, 2));
    expect($formation->getPositionOf04())->toEqual(new Point(2, 2));
    
    expect($formation->getPositionOf05())->toEqual(new Point(5, 5));
    $formation->definePositionOf05(1, 1);
    expect($formation->getPositionOf05())->toEqual(new Point(1, 1));
    $formation->setPositionOf05(new Point(2, 2));
    expect($formation->getPositionOf05())->toEqual(new Point(2, 2));
    
    expect($formation->getPositionOf06())->toEqual(new Point(6, 6));
    $formation->definePositionOf06(1, 1);
    expect($formation->getPositionOf06())->toEqual(new Point(1, 1));
    $formation->setPositionOf06(new Point(2, 2));
    expect($formation->getPositionOf06())->toEqual(new Point(2, 2));
    
    expect($formation->getPositionOf07())->toEqual(new Point(7, 7));
    $formation->definePositionOf07(1, 1);
    expect($formation->getPositionOf07())->toEqual(new Point(1, 1));
    $formation->setPositionOf07(new Point(2, 2));
    expect($formation->getPositionOf07())->toEqual(new Point(2, 2));
    
    expect($formation->getPositionOf08())->toEqual(new Point(8, 8));
    $formation->definePositionOf08(1, 1);
    expect($formation->getPositionOf08())->toEqual(new Point(1, 1));
    $formation->setPositionOf08(new Point(2, 2));
    expect($formation->getPositionOf08())->toEqual(new Point(2, 2));
    
    expect($formation->getPositionOf09())->toEqual(new Point(9, 9));
    $formation->definePositionOf09(1, 1);
    expect($formation->getPositionOf09())->toEqual(new Point(1, 1));
    $formation->setPositionOf09(new Point(2, 2));
    expect($formation->getPositionOf09())->toEqual(new Point(2, 2));
    
    expect($formation->getPositionOf10())->toEqual(new Point(10, 10));
    $formation->definePositionOf10(1, 1);
    expect($formation->getPositionOf10())->toEqual(new Point(1, 1));
    $formation->setPositionOf10(new Point(2, 2));
    expect($formation->getPositionOf10())->toEqual(new Point(2, 2));
    
    expect($formation->getPositionOf11())->toEqual(new Point(11, 11));
    $formation->definePositionOf11(1, 1);
    expect($formation->getPositionOf11())->toEqual(new Point(1, 1));
    $formation->setPositionOf11(new Point(2, 2));
    expect($formation->getPositionOf11())->toEqual(new Point(2, 2));
});

test('DEVE lançar um erro para um jogador que não exite na formação', function () {
    $formation = new Formation([]);

    $formation->getPositionOf(1);
})->throws(InvalidArgumentException::class);

test('DEVE definir e retornar a posição de um jogador', function () {
    $formation = new Formation([]);
    $formation->definePositionOf(1, 10, 20);

    $position = $formation->getPositionOf(1);
    
    expect($position)->toBeInstanceOf(Point::class);
    expect($position->getX())->toEqual(10);
    expect($position->getY())->toEqual(20);
});

test('DEVE criar uma formação com base em um array', function () {
    $positions = [
        1 => [10, 20],
        2 => [30, 40],
    ];
    
    $formation = Formation::createFromArray($positions);

    expect($formation->getPositionOf(1)->getX())->toEqual(10);
    expect($formation->getPositionOf(1)->getY())->toEqual(20);
    expect($formation->getPositionOf(2)->getX())->toEqual(30);
    expect($formation->getPositionOf(2)->getY())->toEqual(40);
});

test('DEVE lançar um erro para um número de jogador inválido', function () {
    $positions = [
        12 => [10, 20], // Número do jogador inválido (fora do intervalo 1-11)
    ];
    
    Formation::createFromArray($positions);
})->throws(InvalidArgumentException::class);

test('DEVE lançar um erro para uma posição inválida', function () {
    $positions = [
        1 => ['invalid', 20],
    ];
    
    Formation::createFromArray($positions);
})->throws(InvalidArgumentException::class);

test('DEVE lançar um erro se a posição definida não é um array com 2 números', function () {
    $positions = [
        1 => [20],
    ];
    
    Formation::createFromArray($positions);
})->throws(InvalidArgumentException::class);

test('DEVE retornar um array de points', function () {
    $players = [
        1 => new Point(1, 1),
        2 => new Point(2, 2),
        3 => new Point(3, 3),
        4 => new Point(4, 4),
        5 => new Point(5, 5),
        6 => new Point(6, 6),
        7 => new Point(7, 7),
        8 => new Point(8, 8),
        9 => new Point(9, 9),
        10 => new Point(0, 0),
        11 => new Point(11, 11),
    ];
    $formation = new Formation($players);

    expect($formation->toArray())->toEqual($players);
});
