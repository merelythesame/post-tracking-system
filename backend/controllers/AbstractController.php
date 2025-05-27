<?php

namespace controllers;

use repository\RepositoryInterface;

abstract class AbstractController
{
    protected RepositoryInterface $repository;

    public function __construct(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    abstract public function getAllEntities(): void;
    abstract public function getEntityById(int $id): void;
    abstract public function addEntity(): void;
    abstract public function updateEntity(int $id): void;
    abstract public function deleteEntity(int $id): void;

}