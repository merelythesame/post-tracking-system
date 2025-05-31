<?php

namespace middleware;

class Dispatcher
{
    private array $middlewareChain;
    private $finalHandler;

    public function __construct(array $middlewareChain, callable $finalHandler) {
        $this->middlewareChain = $middlewareChain;
        $this->finalHandler = $finalHandler;
    }

    public function handle(array $params): void {
        $handler = array_reduce(
            array_reverse($this->middlewareChain),
            fn($next, MiddlewareInterface $middleware) => fn($params) => $middleware->handle($params, $next),
            $this->finalHandler
        );

        $handler($params);
    }

}