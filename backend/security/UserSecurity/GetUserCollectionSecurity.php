<?php

namespace security\UserSecurity;

use config\Security;
use models\User;
use security\SecurityDecorator;

class GetUserCollectionSecurity extends SecurityDecorator
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

        parent::handle($params);
    }

}