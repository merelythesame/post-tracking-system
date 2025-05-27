<?php

namespace security\UserSecurity;

use security\AbstractSecurity;

class AlterUserSecurity extends AbstractSecurity
{
    public function handle(array $params = []): void
    {
        $user = $this->isAuthenticated();
        if (!$user) {
            $this->errorResponse(401, 'Unauthorized - please log in.');
            return;
        }

        $isUserOwner = $this->isUser($user) && $this->isOwner($user, $params[0]);
        $isAdmin = $this->isAdmin($user);

        if ($isUserOwner || $isAdmin) {
            parent::handle($params);
        } else {
            $this->errorResponse(403, 'Forbidden - you do not have access.');
        }

    }

}