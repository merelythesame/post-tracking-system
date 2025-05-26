<?php

namespace routes\SupportTicketStrategies;

use controllers\SupportTicketController;
use routes\RouterStrategyInterface;

class AddSupportTicketStrategy implements RouterStrategyInterface
{

    public function handle(array $params = []): void
    {
        $controller = new SupportTicketController();
        $controller->addTicket();
    }
}