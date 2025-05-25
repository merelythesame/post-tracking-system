<?php

namespace routes\PostOfficeStrategies;

use controllers\PostOfficeController;
use routes\RouterStrategyInterface;

class GetPostOfficeStrategy implements RouterStrategyInterface
{

    public function handle(array $params = []): void
    {
        $controller = new PostOfficeController();
        $controller->getPostOfficeById($params[0]);
    }
}