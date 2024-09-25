<?php

namespace Lugo4php;

enum PlayerState: string {
	case SUPPORTING = "supporting";
    case HOLDING = "holding";
    case DEFENDING = "defending";
    case DISPUTING = "disputing";

    public function is(PlayerState $state): bool
    {
        return $this === $state;
    }

    public static function fromString(string $value): PlayerState
    {
        return match (strtolower($value)) {
            'supporting' => self::SUPPORTING,
            'holding' => self::HOLDING,
            'defending' => self::DEFENDING,
            'disputing' => self::DISPUTING,
            default => throw new \RuntimeException("Valor '{$value}' inválido para estado do player"),
        };
    }
}
