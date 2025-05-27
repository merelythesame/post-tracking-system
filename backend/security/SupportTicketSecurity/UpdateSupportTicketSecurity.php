<?php

namespace security\SupportTicketSecurity;

use config\Security;
use models\User;
use repository\SupportTicketRepository;
use security\AbstractSecurity;
use security\SecurityDecorator;

class UpdateSupportTicketSecurity extends AbstractSecurity
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
            return;
        }

        $this->errorResponse(403, 'Forbidden - you do not have access.');
    }

}