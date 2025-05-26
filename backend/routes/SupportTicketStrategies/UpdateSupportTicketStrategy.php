<?php

namespace routes\SupportTicketStrategies;

use controllers\SupportTicketController;
use routes\RouterStrategyInterface;

class UpdateSupportTicketStrategy implements RouterStrategyInterface
{

    public function handle(array $params = []): void
    {
        $controller = new SupportTicketController();
        $controller->updateTicket($params[0]);
    }
}