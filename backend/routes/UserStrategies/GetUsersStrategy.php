<?php

namespace routes\UserStrategies;

use controllers\UserController;
use routes\RouterStrategyInterface;

class GetUsersStrategy implements RouterStrategyInterface
{

    public function handle(array $params = []): void
    {
        $controller = new UserController();
        $controller->getUsers();
    }
}