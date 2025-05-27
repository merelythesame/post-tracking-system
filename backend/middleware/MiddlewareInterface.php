<?php

namespace middleware;

interface MiddlewareInterface
{
    public function handle(array $params, callable $next): void;
}