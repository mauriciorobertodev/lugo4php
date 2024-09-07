<?php

namespace Lugo4php\Interfaces;

use Lugo4php\Side;

interface IEnv {
    public function getGrpcUrl(): string;
    public function getGrpcInsecure(): bool;
    public function getBotSide(): Side;
    public function getBotNumber(): int;
    public function getBotToken(): string;
}
