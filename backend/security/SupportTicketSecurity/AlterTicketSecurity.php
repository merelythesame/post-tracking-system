<?php

namespace security\SupportTicketSecurity;

use repository\SupportTicketRepository;
use security\AbstractSecurity;

class AlterTicketSecurity extends AbstractSecurity
{
    public function handle(array $params = []): void
    {
        $user = $this->isAuthenticated();
        if (!$user) {
            $this->errorResponse(401, 'Unauthorized - please log in.');
            return;
        }

        $repository = new SupportTicketRepository();
        $ticket = $repository->find($params[0]);

        if (!$ticket) {
            $this->errorResponse(404, 'Ticket not found.');
            return;
        }

        $isOwner = $this->isUser($user) && $this->isOwner($user, $ticket->getUserId());
        $isAdmin = $this->isAdmin($user);

        if ($isOwner || $isAdmin) {
            parent::handle($params);
        } else {
            $this->errorResponse(403, 'Forbidden - you do not have access.');
        }

    }

}