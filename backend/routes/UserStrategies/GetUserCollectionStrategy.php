<?php

namespace routes\UserStrategies;

use config\Security;
use controllers\UserController;
use models\User;
use routes\RouterStrategyInterface;

class GetUserCollectionStrategy implements RouterStrategyInterface
{

    public function handle(array $params = []): void
    {
        $controller = new UserController();
        $controller->getUsers();
    }
}