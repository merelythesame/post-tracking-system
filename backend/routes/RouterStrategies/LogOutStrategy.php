<?php

namespace routes\RouterStrategies;

use controllers\UserController;
use routes\RouterStrategyInterface;

class LogOutStrategy implements RouterStrategyInterface
{

    public function handle(array $params = []): void
    {
        $controller = new UserController();
        $controller->logout();
    }
}