<?php

namespace security;

use routes\RouterStrategyInterface;

class SecurityDecorator implements RouterStrategyInterface
{
    protected RouterStrategyInterface $routerStrategy;

    public function __construct(RouterStrategyInterface $routerStrategy)
    {
        $this->routerStrategy = $routerStrategy;
    }

    public function handle(array $params = []): void
    {
        $this->routerStrategy->handle($params);
    }
}