<?php

namespace routes\RouterStrategies;

use controllers\AbstractController;
use routes\RouterStrategyInterface;

readonly class GetStrategy implements RouterStrategyInterface
{
    function __construct(private AbstractController $controller){}

    public function handle(array $params = []): void
    {
        if (str_contains($params[0], '@')) {
            $this->controller->getEntityByEmail($params[0]);
        } else {
            $this->controller->getEntityById((int) $params[0]);
        }
    }

}