<?php

namespace routes\RouterStrategies;

use controllers\AbstractController;
use controllers\HasUserEntitiesInterface;
use routes\RouterStrategyInterface;

readonly class GetCollectionStrategy implements RouterStrategyInterface
{

    function __construct(private AbstractController $controller){}

    public function handle(array $params = []): void
    {
        if(!empty($params[0]) and $this->controller instanceof HasUserEntitiesInterface){
            $this->controller->getEntityByUser($params[0]);
            return;
        }

        $this->controller->getAllEntities();

    }

}