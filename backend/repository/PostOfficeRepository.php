<?php

namespace repository;

use PDO;
use models\PostOffice;

class PostOfficeRepository extends AbstractRepository implements RepositoryInterface
{
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo, PostOffice::class, 'post_offices');
    }

    public function save(object $entity): int
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO {$this->tableName}
              (name, address, city, postal_code)
            VALUES (?, ?, ?, ?)
        ");

        $stmt->execute([
            $entity->getName(),
            $entity->getAddress(),
            $entity->getCity(),
            $entity->getPostalCode(),
        ]);

        return (int)$this->pdo->lastInsertId();
    }

    public function update(object $entity, array $fields): bool
    {
        if (empty($fields)) {
            return false;
        }

        $setClauses = array_map(fn($k) => "$k = ?", array_keys($fields));
        $values     = array_values($fields);
        $values[]   = $entity->getId();

        $sql  = "UPDATE {$this->tableName} SET " . implode(', ', $setClauses) . " WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute($values);
    }

    protected function hydrate(array $row): object
    {
        $office = new PostOffice();
        $office->setId((int)$row['id']);
        $office->setName($row['name']);
        $office->setAddress($row['address']);
        $office->setCity($row['city']);
        $office->setPostalCode($row['postal_code']);

        return $office;
    }
}
