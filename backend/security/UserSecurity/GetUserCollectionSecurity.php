<?php

namespace security\UserSecurity;

use config\Security;
use models\User;
use security\AbstractSecurity;
use security\SecurityDecorator;

class GetUserCollectionSecurity extends AbstractSecurity
{
    public function handle(array $params = []): void
    {
        $user = $this->isAuthenticated();
        if (!$user) {
            $this->errorResponse(401, 'Unauthorized - please log in.');
            return;
        }

        if ($this->isAdmin($user)) {
            parent::handle($params);
        } else {
            $this->errorResponse(403, 'Forbidden - admin access required.');
        }
    }

}