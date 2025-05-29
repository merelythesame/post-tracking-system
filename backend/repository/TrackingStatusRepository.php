<?php

namespace repository;

use config\Database;
use models\TrackingStatus;
use PDO;

class TrackingStatusRepository implements RepositoryInterface
{
    public function all(): array
    {
        $pdo = Database::getInstance();
        $stmt = $pdo->query("SELECT * FROM tracking_status");

        $trackingStatuses = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $trackingStatuses[] = $this->hydrateTrackingStatus($row);
        }
        return $trackingStatuses;
    }

    public function find(int $id): ?TrackingStatus
    {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("SELECT * FROM tracking_status WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $this->hydrateTrackingStatus($row) : null;
    }

    public function save(object $entity): int
    {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("
            INSERT INTO tracking_status (shipment_id, status, location, send_at, arrive_at, post_office_id)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $entity->getShipmentId(),
            $entity->getStatus(),
            $entity->getLocation(),
            $entity->getSendAt(),
            $entity->getArriveAt(),
            $entity->getPostOfficeId()
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

        $sql = "UPDATE tracking_status SET " . implode(', ', $setClauses) . " WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute($values);
    }

    public function delete(object $entity): bool
    {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("DELETE FROM tracking_status WHERE id = ?");
        return $stmt->execute([$entity->getId()]);
    }

    private function hydrateTrackingStatus(array $row): TrackingStatus
    {
        $trackingStatus = new TrackingStatus();
        $trackingStatus->setId($row['id']);
        $trackingStatus->setShipmentId($row['shipment_id']);
        $trackingStatus->setStatus($row['status']);
        $trackingStatus->setLocation($row['location']);
        $trackingStatus->setSendAt($row['send_at']);
        $trackingStatus->setArriveAt($row['arrive_at']);
        $trackingStatus->setPostOfficeId($row['post_office_id']);

        return $trackingStatus;
    }

}