<?php

namespace routes\UserStrategies;

use controllers\UserController;
use routes\RouterStrategyInterface;

class GetUserStrategy implements RouterStrategyInterface
{

    public function handle(array $params = []): void
    {
        $controller = new UserController();
        $controller->getUserById($params[0]);
    }
}