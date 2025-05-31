<?php

namespace routes\RouterStrategies;

use controllers\AbstractController;
use routes\RouterStrategyInterface;

readonly class AddStrategy implements RouterStrategyInterface
{
    function __construct(private AbstractController $controller){}

    public function handle(array $params = []): void
    {
        $this->controller->addEntity();
    }
}