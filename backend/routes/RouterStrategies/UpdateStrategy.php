<?php

namespace routes\RouterStrategies;

use controllers\AbstractController;
use routes\RouterStrategyInterface;

readonly class UpdateStrategy implements RouterStrategyInterface
{
    function __construct(private AbstractController $controller){}

    public function handle(array $params = []): void
    {
        $this->controller->updateEntity($params[0]);
    }

}