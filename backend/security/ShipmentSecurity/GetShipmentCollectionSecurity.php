<?php

namespace security\ShipmentSecurity;

use config\Security;
use models\User;
use security\SecurityDecorator;

class GetShipmentCollectionSecurity extends SecurityDecorator
{
    public function handle(array $params = []): void
    {
        $currentUser = Security::getUser();

        if(!$currentUser){
            http_response_code(401);
            echo json_encode(['message' => 'Unauthorized - please log in.']);
            return;
        }

        if($currentUser->getRole() === User::ROLE_USER ){
            $params[] = $currentUser->getId();
            parent::handle($params);
            return;
        }

        if($currentUser->getRole() === User::ROLE_ADMIN ){
            parent::handle($params);
            return;
        }

        http_response_code(403);
        echo json_encode(['message' => 'Forbidden - you do not have access.']);

    }

}