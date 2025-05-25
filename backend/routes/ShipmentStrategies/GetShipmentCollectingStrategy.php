<?php

namespace routes\ShipmentStrategies;

use controllers\ShipmentController;
use routes\RouterStrategyInterface;

class GetShipmentCollectingStrategy implements RouterStrategyInterface
{

    public function handle(array $params = []): void
    {
        $controller = new ShipmentController();

        if(!empty($params)){
            $controller->getShipmentsByUser($params[0]);
        }
        else{
            $controller->getShipments();
        }

    }
}