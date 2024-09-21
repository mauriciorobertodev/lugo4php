<?php

namespace Lugo4php;

enum Side: int {
    case HOME = 0;
    case AWAY = 1;

    public static function fromString(string $value): self {
        return match (strtolower($value)) {
            'home' => self::HOME,
            'away' => self::AWAY,
            default => throw new \InvalidArgumentException(sprintf("Valor inválido para o lado do time: '%s'", $value)),
        };
    }

    public function toString(): string {
        return match ($this) {
            self::HOME => 'home',
            self::AWAY => 'away',
        };
    }
}
