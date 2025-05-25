<?php

namespace routes\ShipmentStrategies;

use controllers\ShipmentController;
use routes\RouterStrategyInterface;

class DeleteShipmentStrategy implements RouterStrategyInterface
{

    public function handle(array $params = []): void
    {
        $controller = new ShipmentController();
        $controller->deleteShipment($params[0]);
    }
}