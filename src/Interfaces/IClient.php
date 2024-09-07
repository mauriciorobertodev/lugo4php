<?php

namespace Lugo4php;

use Lugo4php\Interfaces\IBot;

interface IClient {
    public function playAsBot(IBot $bot): void;
}
