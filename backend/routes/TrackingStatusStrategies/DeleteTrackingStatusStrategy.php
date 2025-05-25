<?php

namespace routes\TrackingStatusStrategies;

use controllers\TrackingStatusController;
use routes\RouterStrategyInterface;

class DeleteTrackingStatusStrategy implements RouterStrategyInterface
{

    public function handle(array $params = []): void
    {
        $controller = new TrackingStatusController();
        $controller->deleteTrackingStatus($params[0]);
    }
}