<?php

namespace routes;

interface RouterStrategyInterface
{
    public function handle(array $params = []): void;
}