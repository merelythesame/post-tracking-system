<?php

namespace routes\SupportTicketStrategies;

use controllers\SupportTicketController;
use routes\RouterStrategyInterface;

class DeleteSupportTicketStrategy implements RouterStrategyInterface
{

    public function handle(array $params = []): void
    {
        $controller = new SupportTicketController();
        $controller->deleteTicket($params[0]);
    }
}