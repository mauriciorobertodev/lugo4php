<?php

namespace Lugo4php;

enum FormationType: string {
	case REGIONS = "regions";
    case POINTS = "points";
    case NOT_DEFINED = "";

    public static function fromString(string $value): self {
        return match (strtolower($value)) {
            'regions' => self::REGIONS,
            'points' => self::POINTS,
            '' => self::NOT_DEFINED,
            default => throw new \RuntimeException("Valor '{$value}' inválido para um tipo de formação"),
        };
    }
}
