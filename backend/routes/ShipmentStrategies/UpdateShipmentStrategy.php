<?php

namespace routes\ShipmentStrategies;

use controllers\ShipmentController;
use routes\RouterStrategyInterface;

class UpdateShipmentStrategy implements RouterStrategyInterface
{


    public function handle(array $params = []): void
    {
        $controller = new ShipmentController();
        $controller->updateShipment($params[0]);
    }
}