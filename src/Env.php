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
        $this->grpcUrl = getenv('BOT_GRPC_URL') ? getenv('BOT_GRPC_URL') : 'localhost:5000';
        $this->grpcInsecure = filter_var(getenv('BOT_GRPC_INSECURE') ? getenv('BOT_GRPC_INSECURE') : 'true', FILTER_VALIDATE_BOOLEAN);
        $this->botSide = Side::fromString(strtolower(getenv('BOT_TEAM') ?? ''));
        $this->botNumber = $this->validateBotNumber(getenv('BOT_NUMBER') ?? '');
        $this->botToken = getenv('BOT_TOKEN') ?? '';
        $this->throwIfNeedToken();
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
                sprintf("Número do bot inválido, '%s', deve estar entre 1 e %s", $number, SPECS::MAX_PLAYERS)
            );
        }
        return $number;
    }
    
    private function throwIfNeedToken(): void {
        if(!$this->botToken && !$this->grpcInsecure)  {
            throw new InvalidArgumentException("Partida no modo seguro é necessário definir um token válido");
        }
    }
}
