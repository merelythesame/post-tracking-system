<?php

namespace routes\TrackingStatusStrategies;

use controllers\TrackingStatusController;
use routes\RouterStrategyInterface;

class AddTrackingStatusStrategy implements RouterStrategyInterface
{

    public function handle(array $params = []): void
    {
        $controller = new TrackingStatusController();
        $controller->addTrackingStatus();
    }
}