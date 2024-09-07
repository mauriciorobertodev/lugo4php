<?php

namespace Lugo4php\Interfaces;

use Lugo4php\Interfaces\IBot;

interface IClient {
    public function playAsBot(IBot $bot): void;
}
