<?php

namespace routes\RouterStrategies;

use controllers\AbstractController;
use routes\RouterStrategyInterface;

readonly class GetStrategy implements RouterStrategyInterface
{
    function __construct(private AbstractController $controller){}

    public function handle(array $params = []): void
    {
        $this->controller->getEntityById($params[0]);
    }

}