<?php

namespace repository;

use PDO;
use models\SupportTicket;

class SupportTicketRepository extends AbstractRepository implements RepositoryInterface
{
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo, SupportTicket::class, 'support_tickets');
    }

    public function findByUserId(int $userId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT * FROM {$this->tableName}
            WHERE user_id = ?
        ");
        $stmt->execute([$userId]);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($r) => $this->hydrate($r), $rows);
    }

    public function save(object $entity): int
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO {$this->tableName}
              (user_id, subject, message, response, status, created_at)
            VALUES (?, ?, ?, ?, ?, ?)
        ");

        $stmt->execute([
            $entity->getUserId(),
            $entity->getSubject(),
            $entity->getMessage(),
            $entity->getResponse(),
            $entity->getStatus(),
            $entity->getCreatedAt(),
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
        $t = new SupportTicket();
        $t->setId((int)$row['id']);
        $t->setUserId((int)$row['user_id']);
        $t->setSubject($row['subject']);
        $t->setMessage($row['message']);
        $t->setResponse($row['response']);
        $t->setStatus($row['status']);
        $t->setCreatedAt((int)$row['created_at']);

        return $t;
    }
}
