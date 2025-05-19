<?php

namespace routes\UserStrategies;

use controllers\UserController;
use routes\RouterStrategyInterface;

class UpdateUserStrategy implements RouterStrategyInterface
{
    public function handle(array $params = []): void
    {
        $controller = new UserController();
        $controller->updateUser($params[0]);
    }
}