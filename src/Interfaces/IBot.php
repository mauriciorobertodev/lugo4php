<?php

namespace Lugo4php\Interfaces;

use Lugo4php\GameInspector;
use Lugo4php\PlayerState;

interface IBot {
    public function beforeAction(GameInspector $inspector): void;
    public function onReady(GameInspector $inspector): void;

    public function onHolding(GameInspector $inspector): array;
    public function onDisputing(GameInspector $inspector): array;
    public function onDefending(GameInspector $inspector): array;
    public function onSupporting(GameInspector $inspector): array;

    public function asGoalkeeper(GameInspector $inspector, PlayerState $state): array;
}