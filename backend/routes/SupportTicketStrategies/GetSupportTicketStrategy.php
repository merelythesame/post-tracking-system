<?php

namespace routes\SupportTicketStrategies;

use controllers\SupportTicketController;
use routes\RouterStrategyInterface;

class GetSupportTicketStrategy implements RouterStrategyInterface
{

    public function handle(array $params = []): void
    {
        $controller = new SupportTicketController();
        $controller->getTicketById($params[0]);
    }
}