<?php

namespace repository;

interface RepositoryInterface
{
    public function all(): array;
    public function find(int $id);
    public function save(object $entity): bool;
    public function update(object $entity, array $fields): bool;
    public function delete(object $entity): bool;

}