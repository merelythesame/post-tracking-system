<?php

namespace controllers;

interface HasUserEntitiesInterface
{
    public function getEntityByUser(int $id): void;

}