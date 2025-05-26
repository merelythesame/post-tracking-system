<?php

namespace routes\SupportTicketStrategies;

use controllers\SupportTicketController;
use routes\RouterStrategyInterface;

class GetCollectionSupportTicketStrategy implements RouterStrategyInterface
{

    public function handle(array $params = []): void
    {
        $controller = new SupportTicketController();

        if(!empty($params)){
            $controller->getTicketByUser($params[0]);
        }
        else{
            $controller->getTickets();
        }
    }
}