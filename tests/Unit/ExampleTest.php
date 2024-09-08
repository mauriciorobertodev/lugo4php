<?php

use Lugo4php\Vector2D;

it('pode obter e definir valores x e y', function () {
    $vetor = new Vector2D(1.0, 2.0);

    expect($vetor->getX())->toBe(1.0);
    expect($vetor->getY())->toBe(2.0);

    $vetor->setX(3.0);
    $vetor->setY(4.0);

    expect($vetor->getX())->toBe(3.0);
    expect($vetor->getY())->toBe(4.0);
});

it('pode adicionar valores a x e y', function () {
    $vetor = new Vector2D(1.0, 2.0);

    $vetor->addX(2.0);
    $vetor->addY(3.0);

    expect($vetor->getX())->toBe(3.0);
    expect($vetor->getY())->toBe(5.0);
});

it('pode subtrair valores de x e y', function () {
    $vetor = new Vector2D(5.0, 7.0);

    $vetor->subtractX(2.0);
    $vetor->subtractY(3.0);

    expect($vetor->getX())->toBe(3.0);
    expect($vetor->getY())->toBe(4.0);
});

it('pode escalar valores de x e y', function () {
    $vetor = new Vector2D(1.0, 2.0);

    $vetor->scaleX(3.0);
    $vetor->scaleY(4.0);

    expect($vetor->getX())->toBe(3.0);
    expect($vetor->getY())->toBe(8.0);
});

it('pode dividir valores de x e y', function () {
    $vetor = new Vector2D(6.0, 8.0);

    $vetor->divideX(2.0);
    $vetor->divideY(4.0);

    expect($vetor->getX())->toBe(3.0);
    expect($vetor->getY())->toBe(2.0);
});

it('pode normalizar um vetor', function () {
    $vetor = new Vector2D(3.0, 4.0);
    $normalizado = $vetor->directionTo(new Vector2D(0.0, 0.0));

    expect($normalizado->getX())->toBe(-0.6);
    expect($normalizado->getY())->toBe(-0.8);
});

it('pode mover para uma direção', function () {
    $vetor = new Vector2D(1.0, 1.0);
    $direcao = new Vector2D(1.0, 0.0);
    $movido = $vetor->moveToDirection($direcao, 5.0);

    expect($movido->getX())->toBe(6.0);
    expect($movido->getY())->toBe(1.0);
});
