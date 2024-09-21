<?php

use Lugo4php\Interfaces\IPositionable;
use Lugo4php\Point;
use Lugo4php\Vector2D;
use Tests\Classes\Positionable;

use Lugo\Point as LugoPoint;
use Lugo\Vector as LugoVector;

test('DEVE criar um novo Positionable com base em um LugoPoint e um LugoVector e ter os mesmos dados', function () {
    $lp = randomLugoPoint();
    $vp = randomLugoVector();

    $pos1 = Positionable::fromLugoPoint($lp);
    $pos2 = Positionable::fromLugoVector($vp);

    expect($pos1)->toBeInstanceOf(Positionable::class);
    expect($pos1->getX())->toEqual($lp->getX());
    expect($pos1->getY())->toEqual($lp->getY());

    expect($pos2)->toBeInstanceOf(Positionable::class);
    expect($pos2->getX())->toEqual($vp->getX());
    expect($pos2->getY())->toEqual($vp->getY());

    $pos1 = Point::fromLugoPoint($lp);
    $pos2 = Point::fromLugoVector($vp);

    expect($pos1)->toBeInstanceOf(IPositionable::class);
    expect($pos1->getX())->toEqual($lp->getX());
    expect($pos1->getY())->toEqual($lp->getY());

    expect($pos2)->toBeInstanceOf(IPositionable::class);
    expect($pos2->getX())->toEqual($vp->getX());
    expect($pos2->getY())->toEqual($vp->getY());

    $pos1 = Vector2D::fromLugoPoint($lp);
    $pos2 = Vector2D::fromLugoVector($vp);

    expect($pos1)->toBeInstanceOf(IPositionable::class);
    expect($pos1->getX())->toEqual($lp->getX());
    expect($pos1->getY())->toEqual($lp->getY());

    expect($pos2)->toBeInstanceOf(IPositionable::class);
    expect($pos2->getX())->toEqual($vp->getX());
    expect($pos2->getY())->toEqual($vp->getY());
});

test('DEVE definir, adicionar, subtrair, escalar e dividir o eixo X', function () {
    $pos = new Positionable(0,0);
    expect($pos->getX())->toEqual(0);

    $pos->setX(10);
    expect($pos->getX())->toEqual(10);
    
    $pos->addX(24);
    expect($pos->getX())->toEqual(34);
    
    $pos->subtractX(2);
    expect($pos->getX())->toEqual(32);

    $pos->scaleX(2);
    expect($pos->getX())->toEqual(64);

    $pos->divideX(8);
    expect($pos->getX())->toEqual(8);
});

test('DEVE definir, adicionar, subtrair, escalar e dividir o eixo Y', function () {
    $pos = new Positionable(0,0);
    expect($pos->getY())->toEqual(0);

    $pos->setY(10);
    expect($pos->getY())->toEqual(10);
    
    $pos->addY(24);
    expect($pos->getY())->toEqual(34);
    
    $pos->subtractY(2);
    expect($pos->getY())->toEqual(32);

    $pos->scaleY(2);
    expect($pos->getY())->toEqual(64);

    $pos->divideY(8);
    expect($pos->getY())->toEqual(8);
});

test('DEVE adicionar, subtrair, escalar e dividir em ambos os eixos, usando um outro Positionable', function () {
    $pos = new Positionable(0,0);
    expect($pos->getX())->toEqual(0);
    expect($pos->getY())->toEqual(0);
    
    $result = $pos->add(new Positionable(34, 64));
    expect($result->getX())->toEqual(34);
    expect($result->getY())->toEqual(64);
    expect($pos)->toBe($result);
    
    $result = $pos->subtract(new Positionable(2, 32));
    expect($result->getX())->toEqual(32);
    expect($result->getY())->toEqual(32);
    expect($pos)->toBe($result);

    $result = $pos->scale(new Positionable(2, 4));
    expect($result->getX())->toEqual(64);
    expect($result->getY())->toEqual(128);
    expect($pos)->toBe($result);

    $result = $pos->divide(new Positionable(8, 16));
    expect($result->getX())->toEqual(8);
    expect($result->getY())->toEqual(8);
    expect($pos)->toBe($result);
});

test('DEVE retornar um clone adicionado, subtraído, escalonado e dividido em ambos os eixos, usando um outro Positionable', function () {
    $pos = new Positionable(3,4);
    expect($pos->getX())->toEqual(3);
    expect($pos->getY())->toEqual(4);
    
    $result = $pos->added(new Positionable(34, 64));
    expect($result->getX())->toEqual(37);
    expect($result->getY())->toEqual(68);
    expect($pos)->not()->toBe($result);
    
    $result = $pos->subtracted(new Positionable(2, 32));
    expect($result->getX())->toEqual(1);
    expect($result->getY())->toEqual(-28);
    expect($pos)->not()->toBe($result);

    $result = $pos->scaled(new Positionable(2, 4));
    expect($result->getX())->toEqual(6);
    expect($result->getY())->toEqual(16);
    expect($pos)->not()->toBe($result);

    $result = $pos->divided(new Positionable(8, 16));
    expect($result->getX())->toEqual(0.375);
    expect($result->getY())->toEqual(0.25);
    expect($pos)->not()->toBe($result);
});

test('DEVE adicionar, subtrair, escalar e dividir em ambos os eixos, usando um valor', function () {
    $pos = new Positionable(0,0);
    expect($pos->getX())->toEqual(0);
    expect($pos->getY())->toEqual(0);
    
    $pos->add(34);
    expect($pos->getX())->toEqual(34);
    expect($pos->getY())->toEqual(34);
    
    $pos->subtract(2);
    expect($pos->getX())->toEqual(32);
    expect($pos->getY())->toEqual(32);

    $pos->scale(2);
    expect($pos->getX())->toEqual(64);
    expect($pos->getY())->toEqual(64);

    $pos->divide(8);
    expect($pos->getX())->toEqual(8);
    expect($pos->getY())->toEqual(8);
});

test('DEVE retornar um clone da classe', function () {
    $pos = new Positionable(0,0);
    expect($pos)->toBe($pos);

    $cloned = $pos->clone();
    expect($pos)->not()->toBe($cloned);
    expect($pos)->toEqual($cloned);

    $cloned->setX(500);
    expect($pos)->not()->toEqual($cloned);
});

test('DEVE retornar a magnitude', function (int $x, int $y, float $expected) {
    $pos = new Positionable($x, $y);

    $magnitude = $pos->magnitude();

    expect($magnitude)->toBe($expected);
})->with([
    [3, 4, 5.0],
    [-3, -4, 5.0],
    [0, 0, 0.0],
    [5, 12, 13.0],
    [-8, 15, 17.0],
    [7, -24, 25.0],
]);

test('DEVE retornar a direção correta', function (int $startX, int $startY, int $endX, int $endY, float $expectedX, float $expectedY) {
    $start = new Positionable($startX, $startY);
    $end = new Positionable($endX, $endY);

    $direction = $start->directionTo($end);

    expect($direction->getX())->toBe($expectedX);
    expect($direction->getY())->toBe($expectedY);
})->with([
    [0, 0, 3, 4, 0.6, 0.8],
    [1, 1, 4, 5, 0.6, 0.8],
    [0, 0, -3, -4, -0.6, -0.8],
    [2, 2, 2, 5, 0.0, 1.0],
    [5, 5, 5, 5, 0.0, 0.0],
]);

test('DEVE retornar a distância correta', function (int $startX, int $startY, int $endX, int $endY, float $expected) {
    $start = new Positionable($startX, $startY);
    $end = new Positionable($endX, $endY);

    $distance = $start->distanceTo($end);

    $p = randomLugoPoint();
    $p->setX(33.333333);

    expect($distance)->toBe($expected);
})->with([
    [0, 0, 3, 4, 5.0],
    [1, 1, 4, 5, 5.0],
    [0, 0, -3, -4, 5.0],
    [2, 2, 2, 5, 3.0],
    [5, 5, 5, 5, 0.0],
    [1, 1, 1, 1, 0.0],
]);

test('DEVE mover corretamente na direção dada', function (int $startX, int $startY, float $dirX, float $dirY, float $distance, int $expectedX, int $expectedY) {
    $start = new Positionable($startX, $startY);
    $direction = new Vector2D($dirX, $dirY);

    $moved = $start->moveToDirection($direction, $distance);

    expect($moved->getX())->toEqual($expectedX);
    expect($moved->getY())->toEqual($expectedY);
    expect($start)->toBe($moved);
})->with([
    [0, 0, 1, 0, 5, 5, 0],     // 5 unidades pra direita
    [0, 0, 0, 1, 3, 0, 3],     // 3 unidades pra cima
    [0, 0, -1, 0, 4, -4, 0],   // 4 unidades pra esquerda
    [0, 0, 0, -1, 2, 0, -2],   // 2 unidades pra baixo
    [1, 1, 0.6, 0.8, 5, 4, 5], // 5 unidades pra cima e direita
    [1, 1, 0.6, -0.8, 5, 4, -3], // 5 unidades pra baixo e direita
]);

test('DEVE retornar um clone movido corretamente na direção dada', function (int $startX, int $startY, float $dirX, float $dirY, float $distance, int $expectedX, int $expectedY) {
    $start = new Positionable($startX, $startY);
    $direction = new Vector2D($dirX, $dirY);

    $moved = $start->movedToDirection($direction, $distance);

    expect($moved->getX())->toEqual($expectedX);
    expect($moved->getY())->toEqual($expectedY);
    expect($start)->not()->toBe($moved);
})->with([
    [0, 0, 1, 0, 5, 5, 0],     // 5 unidades pra direita
    [0, 0, 0, 1, 3, 0, 3],     // 3 unidades pra cima
    [0, 0, -1, 0, 4, -4, 0],   // 4 unidades pra esquerda
    [0, 0, 0, -1, 2, 0, -2],   // 2 unidades pra baixo
    [1, 1, 0.6, 0.8, 5, 4, 5], // 5 unidades pra cima e direita
    [1, 1, 0.6, -0.8, 5, 4, -3], // 5 unidades pra baixo e direita
]);

test('DEVE mover corretamente para o ponto', function (int $startX, int $startY, int $pointX, int $pointY, float $distance, float $expectedX, float $expectedY) {
    $start = new Positionable($startX, $startY);
    $point = new Point($pointX, $pointY);

    $moved = $start->moveToPoint($point, $distance);

    expect($moved->getX())->toEqual($expectedX);
    expect($moved->getY())->toEqual($expectedY);
    expect($start)->toBe($moved);
})->with([
    [0, 0, 3, 4, 5, 3, 4],
     [1, 1, 4, 5, 3, 2.8, 3.4],
    [0, 0, -3, -4, 5, -3, -4],
    [2, 2, 2, 5, 3, 2, 5],
    [5, 5, 0, 0, 5, 1.464466094, 1.464466094],
]);

test('DEVE retornar um clone movido corretamente para o ponto', function (int $startX, int $startY, int $pointX, int $pointY, float $distance, float $expectedX, float $expectedY) {
    $start = new Positionable($startX, $startY);
    $point = new Point($pointX, $pointY);

    $moved = $start->movedToPoint($point, $distance);

    expect($moved->getX())->toEqual($expectedX);
    expect($moved->getY())->toEqual($expectedY);
    expect($start)->not()->toBe($moved);
})->with([
    [0, 0, 3, 4, 5, 3, 4],
    [1, 1, 4, 5, 3, 2.8, 3.4],
    [0, 0, -3, -4, 5, -3, -4],
    [2, 2, 2, 5, 3, 2, 5],
    [5, 5, 0, 0, 5, 1.464466094, 1.464466094],
]);

test('DEVE retornar um LugoPoint com mesmos dados', function () {
    $pos = new Positionable(500, 200);
    $point = $pos->toLugoPoint();

    expect($point)->toBeInstanceOf(LugoPoint::class);
    expect($point->getX())->toEqual($pos->getX());
    expect($point->getY())->toEqual($pos->getY());
  
    // Points do lugo são arredondados automaticamente, coisa do gRPC
    $pos = new Positionable(500.55, 200.66);
    $point = $pos->toLugoPoint();

    expect($point)->toBeInstanceOf(LugoPoint::class);
    expect($point->getX())->not()->toEqual($pos->getX());
    expect($point->getY())->not()->toEqual($pos->getY());
    expect($point->getX())->toEqual(500);
    expect($point->getY())->toEqual(200);
});

test('DEVE retornar um LugoVector com mesmos dados', function () {
    $pos = new Positionable(500, 200);
    $point = $pos->toLugoVector();

    expect($point)->toBeInstanceOf(LugoVector::class);
    expect($point->getX())->toEqual($pos->getX());
    expect($point->getY())->toEqual($pos->getY());
  
    // Vectors do lugo  NÃO são arredondados automaticamente
    $pos = new Positionable(500.55, 200.66);
    $point = $pos->toLugoVector();

    expect($point)->toBeInstanceOf(LugoVector::class);
    expect($point->getX())->toEqual($pos->getX());
    expect($point->getY())->toEqual($pos->getY());
});

test('DEVE ser convertido para uma string', function () {
    $pos = new Positionable(500, 200);
    $string = sprintf("%s", $pos);
    
    expect($string)->toBe("(500.00, 200.00)");
});

test('DEVE retornar um clone em Point ou Vector', function () {
    $pos = new Positionable(555, 222);

    $p = $pos->toPoint();
    expect($p)->toBeInstanceOf(Point::class);
    expect($p->getX())->toEqual(555);
    expect($p->getY())->toEqual(222);

    $v = $pos->toVector2D();
    expect($v)->toBeInstanceOf(Vector2D::class);
    expect($v->getX())->toEqual(555);
    expect($v->getY())->toEqual(222);
});

test('DEVE se o positionable tem o mesmo X e Y de outro positionable', function () {
    $pos = new Positionable(555, 222);
    $p1 = new Point(555, 222);
    $v1 = new Vector2D(555, 222);
    $p2 = new Point(333, 333);
    $v2 = new Vector2D(333, 333);

    expect($pos->is($p1))->toBeTrue();
    expect($pos->is($v1))->toBeTrue();
    expect($pos->eq($p1))->toBeTrue();
    expect($pos->eq($v1))->toBeTrue();

    expect($pos->is($p2))->toBeFalse();
    expect($pos->is($v2))->toBeFalse();
    expect($pos->eq($p2))->toBeFalse();
    expect($pos->eq($v2))->toBeFalse();
});
