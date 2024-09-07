<?php

namespace Lugo4php;

enum PlayerState: string {
	case SUPPORTING = "supporting";
    case HOLDING = "holding";
    case DEFENDING = "defending";
    case DISPUTING = "disputing";

    public static function fromString(string $value): self {
        return match (strtolower($value)) {
            'supporting' => self::SUPPORTING,
            'holding' => self::HOLDING,
            'defending' => self::DEFENDING,
            'disputing' => self::DISPUTING,
            default => throw new \InvalidArgumentException("Invalid team side value: '{$value}'"),
        };
    }
}
