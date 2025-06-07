<?php

namespace repository;

use PDO;
use models\TrackingStatus;

class TrackingStatusRepository extends AbstractRepository implements RepositoryInterface
{
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo, TrackingStatus::class, 'tracking_status');
    }

    public function save(object $entity): int
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO {$this->tableName}
              (shipment_id, status, location, send_at, arrive_at)
            VALUES (?, ?, ?, ?, ?)
        ");

        $stmt->execute([
            $entity->getShipmentId(),
            $entity->getStatus(),
            $entity->getLocation(),
            $entity->getSendAt(),
            $entity->getArriveAt(),
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
        $ts = new TrackingStatus();
        $ts->setId((int)$row['id']);
        $ts->setShipmentId((int)$row['shipment_id']);
        $ts->setStatus($row['status']);
        $ts->setLocation($row['location']);
        $ts->setSendAt((int)$row['send_at']);
        $ts->setArriveAt((int)$row['arrive_at']);

        return $ts;
    }
}
