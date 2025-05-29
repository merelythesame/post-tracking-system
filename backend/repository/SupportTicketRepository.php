<?php

namespace repository;

use config\Database;
use models\SupportTicket;
use PDO;

class SupportTicketRepository implements RepositoryInterface
{
    public function all(): array
    {
        $pdo = Database::getInstance();
        $stmt = $pdo->query("SELECT * FROM support_tickets");

        $tickets = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $tickets[] = $this->hydrate($row);
        }

        return $tickets;
    }

    public function find(int $id): ?SupportTicket
    {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("SELECT * FROM support_tickets WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? $this->hydrate($row) : null;
    }

    public function findByUserId(int $userId): array
    {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("SELECT * FROM support_tickets WHERE user_id = ?");
        $stmt->execute([$userId]);

        $tickets = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $tickets[] = $this->hydrate($row);
        }

        return $tickets;
    }

    public function save(object $entity): int
    {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("
            INSERT INTO support_tickets (user_id, subject, message, response, status, created_at)
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
        $sql = "UPDATE support_tickets SET " . implode(', ', $setClauses) . " WHERE id = ?";
        $stmt = $pdo->prepare($sql);

        return $stmt->execute($values);
    }

    public function delete(object $entity): bool
    {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("DELETE FROM support_tickets WHERE id = ?");
        return $stmt->execute([$entity->getId()]);
    }

    private function hydrate(array $row): SupportTicket
    {
        $ticket = new SupportTicket();
        $ticket->setId($row['id']);
        $ticket->setUserId($row['user_id']);
        $ticket->setSubject($row['subject']);
        $ticket->setMessage($row['message']);
        $ticket->setResponse($row['response']);
        $ticket->setStatus($row['status']);
        $ticket->setCreatedAt($row['created_at']);

        return $ticket;
    }

}