<?php

namespace Lugo4php;

enum Direction: int
{
    case FORWARD = 0;
    case BACKWARD = 1;
    case LEFT = 2;
    case RIGHT = 3;
    case BACKWARD_LEFT = 4;
    case BACKWARD_RIGHT = 5;
    case FORWARD_LEFT = 6;
    case FORWARD_RIGHT = 7;

    public static function fromValue(int $value): self
    {
        return match ($value) {
            0 => self::FORWARD,
            1 => self::BACKWARD,
            2 => self::LEFT,
            3 => self::RIGHT,
            4 => self::BACKWARD_LEFT,
            5 => self::BACKWARD_RIGHT,
            6 => self::FORWARD_LEFT,
            7 => self::FORWARD_RIGHT,
            default => throw new \InvalidArgumentException("Direção inválida: $value"),
        };
    }
}
