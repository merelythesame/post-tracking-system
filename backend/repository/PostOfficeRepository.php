<?php

namespace repository;

use config\Database;
use models\PostOffice;
use PDO;

class PostOfficeRepository
{
    public function all(): array
    {
        $pdo = Database::getInstance();
        $stmt = $pdo->query("SELECT * FROM post_offices");

        $offices = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $offices[] = $this->hydratePostOffice($row);
        }

        return $offices;
    }

    public function find(int $id): ?PostOffice
    {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("SELECT * FROM post_offices WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $this->hydratePostOffice($row) : null;
    }

    public function save(PostOffice $office): bool
    {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("
            INSERT INTO post_offices (name, address, city, postal_code)
            VALUES (?, ?, ?, ?)
        ");
        return $stmt->execute([
            $office->getName(),
            $office->getAddress(),
            $office->getCity(),
            $office->getPostalCode(),
        ]);
    }

    public function update(PostOffice $office, array $fields): bool
    {
        $pdo = Database::getInstance();

        $setClauses = [];
        $values = [];

        foreach ($fields as $key => $value) {
            $setClauses[] = "$key = ?";
            $values[] = $value;
        }

        if (empty($setClauses)) return false;

        $values[] = $office->getId();
        $sql = "UPDATE post_offices SET " . implode(', ', $setClauses) . " WHERE id = ?";
        $stmt = $pdo->prepare($sql);

        return $stmt->execute($values);
    }

    public function delete(PostOffice $office): bool
    {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("DELETE FROM post_offices WHERE id = ?");
        return $stmt->execute([$office->getId()]);
    }

    private function hydratePostOffice(array $row): PostOffice
    {
        $office = new PostOffice();
        $office->setId($row['id']);
        $office->setName($row['name']);
        $office->setAddress($row['address']);
        $office->setCity($row['city']);
        $office->setPostalCode($row['postal_code']);

        return $office;
    }

}