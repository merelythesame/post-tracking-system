<?php

namespace routes\UserStrategies;

use controllers\UserController;
use routes\RouterStrategyInterface;

class DeleteUserStrategy implements RouterStrategyInterface
{
    public function handle(array $params = []): void
    {
        $controller = new UserController();
        $controller->deleteUser($params[0]);
    }
}