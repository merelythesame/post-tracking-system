<?php

namespace repository;

use PDO;
use repository\Exception\EntityNotFoundException;

abstract class AbstractRepository
{
    protected PDO $pdo;
    protected string $entityClass;
    protected string $tableName;

    public function __construct(PDO $pdo, string $entityClass, string $tableName)
    {
        $this->pdo         = $pdo;
        $this->entityClass = $entityClass;
        $this->tableName   = $tableName;
    }

    public function all(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM {$this->tableName}");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(fn(array $row) => $this->hydrate($row), $rows);
    }

    public function find(int $id): ?object
    {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->tableName} WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row !== false
            ? $this->hydrate($row)
            : null;
    }

    public function delete(object $entity): bool
    {
        if (!method_exists($entity, 'getId')) {
            throw new EntityNotFoundException("Entity has no getId method");
        }

        $stmt = $this->pdo->prepare("DELETE FROM {$this->tableName} WHERE id = ?");
        return $stmt->execute([$entity->getId()]);
    }

    abstract protected function hydrate(array $row): object;
}
