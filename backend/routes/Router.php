<?php

namespace routes;

class Router
{
    private $routes = [];

    public function register(string $method, string $pattern, RouterStrategyInterface $strategy): void {
        $this->routes[] = [
            'method' => $method,
            'pattern' => $pattern,
            'strategy' => $strategy
        ];
    }

    public function resolve(string $uri, string $method): ?array
    {
        foreach ($this->routes as $route) {
            if ($route['method'] === $method && preg_match($route['pattern'], $uri, $matches)) {
                array_shift($matches);
                return [$route['strategy'], $matches];
            }
        }
        return null;
    }

}