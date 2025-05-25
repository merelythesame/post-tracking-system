<?php

namespace routes\PostOfficeStrategies;

use controllers\PostOfficeController;
use routes\RouterStrategyInterface;

class GetCollectionPostOfficeStrategy implements RouterStrategyInterface
{

    public function handle(array $params = []): void
    {
        $controller = new PostOfficeController();
        $controller->getPostOffices();
    }
}