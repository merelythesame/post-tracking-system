<?php

namespace repository;

use config\Database;
use models\TrackingStatus;
use PDO;

class TrackingStatusRepository
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

    public function save(TrackingStatus $trackingStatus): bool
    {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("
            INSERT INTO tracking_status (shipment_id, status, location, send_at, arrive_at, post_office_id)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $trackingStatus->getShipmentId(),
            $trackingStatus->getStatus(),
            $trackingStatus->getLocation(),
            $trackingStatus->getSendAt(),
            $trackingStatus->getArriveAt(),
            $trackingStatus->getPostOfficeId()
        ]);
    }

    public function update(TrackingStatus $trackingStatus, array $fields): bool
    {
        $pdo = Database::getInstance();

        $setClauses = [];
        $values = [];

        foreach ($fields as $key => $value) {
            $setClauses[] = "$key = ?";
            $values[] = $value;
        }

        if (empty($setClauses)) return false;

        $values[] = $trackingStatus->getId();

        $sql = "UPDATE tracking_status SET " . implode(', ', $setClauses) . " WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute($values);
    }

    public function delete(TrackingStatus $trackingStatus): bool
    {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("DELETE FROM tracking_status WHERE id = ?");
        return $stmt->execute([$trackingStatus->getId()]);
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