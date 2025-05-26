<?php

namespace security\SupportTicketSecurity;

use config\Security;
use models\User;
use repository\SupportTicketRepository;
use security\SecurityDecorator;

class GetSupportTicketSecurity extends SecurityDecorator
{
    public function handle(array $params = []): void
    {
        $currentUser = Security::getUser();

        if(!$currentUser){
            http_response_code(401);
            echo json_encode(['message' => 'Unauthorized - please log in.']);
            return;
        }

        $repository = new SupportTicketRepository();
        $ticket = $repository->find($params[0]);

        if(!$ticket){
            http_response_code(404);
            echo json_encode(['message' => 'Ticket not found.']);
            return;
        }

        if(($currentUser->getRole() === User::ROLE_USER and $currentUser->getId() == $ticket->getUserId()) or $currentUser->getRole() === User::ROLE_ADMIN){
            parent::handle($params);
            return;
        }

        http_response_code(403);
        echo json_encode(['message' => 'Forbidden - you do not have access.']);

    }

}