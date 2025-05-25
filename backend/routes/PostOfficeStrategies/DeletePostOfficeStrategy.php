<?php

namespace routes\PostOfficeStrategies;

use controllers\PostOfficeController;
use routes\RouterStrategyInterface;

class DeletePostOfficeStrategy implements RouterStrategyInterface
{

    public function handle(array $params = []): void
    {
        $controller = new PostOfficeController();
        $controller->deleteOffice($params[0]);
    }
}