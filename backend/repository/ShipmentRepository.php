<?php

namespace repository;

use config\Database;
use models\Shipment;
use PDO;

class ShipmentRepository implements RepositoryInterface
{
    public function all(): array
    {
        $pdo = Database::getInstance();
        $stmt = $pdo->query("SELECT * FROM shipments");

        $shipments = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $shipments[] = $this->hydrateShipment($row);
        }
        return $shipments;
    }

    public function find(int $id): ?Shipment
    {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("SELECT * FROM shipments WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $this->hydrateShipment($row) : null;
    }

    public function findByUserId(int $userId): array
    {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("SELECT * FROM shipments WHERE user_id = ? OR receiver_id = ?");
        $stmt->execute([$userId, $userId]);

        $shipments = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $shipments[] = $this->hydrateShipment($row);
        }
        return $shipments;
    }

    public function save(object $entity): int
    {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("
        INSERT INTO shipments (user_id, receiver_id, receiver_name, sender_name, address, weight, type, created_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");

        $stmt->execute([
            $entity->getUserId(),
            $entity->getReceiverId(),
            $entity->getReceiverName(),
            $entity->getSenderName(),
            $entity->getAddress(),
            $entity->getWeight(),
            $entity->getType(),
            time(),
        ]);

        return (int) $pdo->lastInsertId();
    }

    public function update(object $entity, array $fields): bool
    {
        $pdo = Database::getInstance();

        $setClauses = [];
        $values = [];

        foreach ($fields as $key => $value) {
            $setClauses[] = "$key = ?";
            $values[] = $value;
        }

        if (empty($setClauses)) return false;

        $values[] = $entity->getId();

        $sql = "UPDATE shipments SET " . implode(', ', $setClauses) . " WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute($values);
    }

    public function delete(object $entity): bool
    {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("DELETE FROM shipments WHERE id = ?");
        return $stmt->execute([$entity->getId()]);
    }

    private function hydrateShipment(array $row): Shipment
    {
        $shipment = new Shipment();
        $shipment->setId($row["id"]);
        $shipment->setUserId($row["user_id"]);
        $shipment->setReceiverName($row["receiver_name"]);
        $shipment->setSenderName($row["sender_name"]);
        $shipment->setReceiverId($row["receiver_id"]);
        $shipment->setAddress($row["address"]);
        $shipment->setWeight($row["weight"]);
        $shipment->setType($row["type"]);
        $shipment->setCreatedAt($row["created_at"]);

        return $shipment;
    }

}