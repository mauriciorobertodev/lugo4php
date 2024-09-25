<?php

namespace Lugo4php\Interfaces;

use Lugo4php\GameInspector;
use Lugo4php\PlayerState;

interface IBot {
    public function beforeActions(GameInspector $inspector): void;
    public function afterActions(GameInspector $inspector): void;
    
    public function onReady(GameInspector $inspector): void;

    /** @return \Lugo\Order[] */
    public function onHolding(GameInspector $inspector): array;
    
    /** @return \Lugo\Order[] */
    public function onDisputing(GameInspector $inspector): array;

    /** @return \Lugo\Order[] */
    public function onDefending(GameInspector $inspector): array;

    /** @return \Lugo\Order[] */
    public function onSupporting(GameInspector $inspector): array;

    /** @return \Lugo\Order[] */
    public function asGoalkeeper(GameInspector $inspector, PlayerState $state): array;
}