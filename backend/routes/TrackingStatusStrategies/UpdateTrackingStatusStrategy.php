<?php

namespace routes\TrackingStatusStrategies;

use controllers\TrackingStatusController;
use routes\RouterStrategyInterface;

class UpdateTrackingStatusStrategy implements RouterStrategyInterface
{

    public function handle(array $params = []): void
    {
        $controller = new TrackingStatusController();
        $controller->updateTrackingStatus($params[0]);
    }
}