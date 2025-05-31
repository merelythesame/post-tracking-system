<?php

namespace routes\RouterStrategies;

use controllers\UserController;
use routes\RouterStrategyInterface;

class LogInUserStrategy implements RouterStrategyInterface
{
    public function handle(array $params = []): void
    {
        $controller = new UserController();
        $controller->login();
    }

}