<?php

namespace routes\TrackingStatusStrategies;

use controllers\TrackingStatusController;
use routes\RouterStrategyInterface;

class GetTrackingStatusStrategy implements RouterStrategyInterface
{

    public function handle(array $params = []): void
    {
        $controller = new TrackingStatusController();
        $controller->getTrackingStatusById($params[0]);
    }
}