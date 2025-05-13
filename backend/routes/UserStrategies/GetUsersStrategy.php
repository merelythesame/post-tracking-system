<?php

namespace routes\UserStrategies;

use config\Security;
use controllers\UserController;
use models\User;
use routes\RouterStrategyInterface;

class GetUsersStrategy implements RouterStrategyInterface
{

    public function handle(array $params = []): void
    {
        $currentUser = Security::getUser();

        if(!$currentUser){
            http_response_code(401);
            echo json_encode(['message' => 'Unauthorized - please log in.']);
            return;
        }

        if($currentUser->role == User::ROLE_USER){
            http_response_code(403);
            echo json_encode(['message' => 'Forbidden - you do not have access.']);
            return;
        }

        $controller = new UserController();
        $controller->getUsers();
    }
}