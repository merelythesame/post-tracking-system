<?php

namespace routes\ShipmentStrategies;

use controllers\ShipmentController;
use routes\RouterStrategyInterface;

class AddShipmentStrategy implements RouterStrategyInterface
{

    public function handle(array $params = []): void
    {
        $controller = new ShipmentController();
        $controller->addShipment();
    }
}