<?php

namespace routes\PostOfficeStrategies;

use controllers\PostOfficeController;
use routes\RouterStrategyInterface;

class UpdatePostOfficeStrategy implements RouterStrategyInterface
{

    public function handle(array $params = []): void
    {
        $controller = new PostOfficeController();
        $controller->updateOffice($params[0]);
    }
}