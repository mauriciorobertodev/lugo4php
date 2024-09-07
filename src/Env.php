<?php

namespace Lugo4php;

use InvalidArgumentException;
use Lugo4php\Interfaces\IEnv;
use Lugo4php\Side;
use Lugo4php\SPECS;

class Env implements IEnv {
    private string $grpcUrl;
    private bool $grpcInsecure;
    private Side $botSide; // Supondo que Side é um enum
    private int $botNumber;
    private string $botToken;

    public function __construct() {
        $this->grpcUrl = $_ENV['BOT_GRPC_URL'] ?? 'localhost:5000';
        $this->grpcInsecure = filter_var($_ENV['BOT_GRPC_INSECURE'] ?? 'true', FILTER_VALIDATE_BOOLEAN);
        $this->botSide = Side::fromString(strtolower($_ENV['BOT_TEAM'] ?? 'home'));
        $this->botNumber = $this->validateBotNumber($_ENV['BOT_NUMBER'] ?? '10');
        $this->botToken = $_ENV['BOT_TOKEN'] ?? '';
    }

    public function getGrpcUrl(): string {
        return $this->grpcUrl;
    }

    public function getGrpcInsecure(): bool {
        return $this->grpcInsecure;
    }

    public function getBotSide(): Side {
        return $this->botSide;
    }

    public function getBotNumber(): int {
        return $this->botNumber;
    }

    public function getBotToken(): string {
        return $this->botToken;
    }

    private function validateBotNumber(string $botNumber): int {
        $number = (int) $botNumber;
        if ($number < 1 || $number > SPECS::MAX_PLAYERS) {
            throw new InvalidArgumentException(
                "Invalid bot number '{$number}', must be between 1 and " . SPECS::MAX_PLAYERS
            );
        }
        return $number;
    }
}
