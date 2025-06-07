<?php

namespace repository;

use PDO;
use models\Shipment;

class ShipmentRepository extends AbstractRepository implements RepositoryInterface
{
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo, Shipment::class, 'shipments');
    }

    public function findByUserId(int $userId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT * FROM {$this->tableName}
            WHERE user_id = ? OR receiver_id = ?
        ");
        $stmt->execute([$userId, $userId]);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($r) => $this->hydrate($r), $rows);
    }

    public function save(object $entity): int
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO {$this->tableName}
              (user_id, receiver_id, receiver_name, sender_name,
               weight, type, created_at, send_office, receive_office)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->execute([
            $entity->getUserId(),
            $entity->getReceiverId(),
            $entity->getReceiverName(),
            $entity->getSenderName(),
            $entity->getWeight(),
            $entity->getType(),
            time(),
            $entity->getSendOffice(),
            $entity->getReceiveOffice(),
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
        $s = new Shipment();
        $s->setId((int)$row['id']);
        $s->setUserId((int)$row['user_id']);
        $s->setReceiverId((int)$row['receiver_id']);
        $s->setReceiverName($row['receiver_name']);
        $s->setSenderName($row['sender_name']);
        $s->setWeight((float)$row['weight']);
        $s->setType($row['type']);
        $s->setCreatedAt((int)$row['created_at']);
        $s->setSendOffice((int)$row['send_office']);
        $s->setReceiveOffice((int)$row['receive_office']);

        return $s;
    }
}
